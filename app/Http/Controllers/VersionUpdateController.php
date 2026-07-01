<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use File;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class VersionUpdateController extends Controller
{
    protected $logger;
    public function __construct()
    {
        $this->logger = new Logger(storage_path('logs/update.log'));
    }

    public function versionUpdate(Request $request)
    {
        $data['title'] = __('Version Update');

        return view('zainiklab.installer.version-update', $data);
    }

    public function processUpdate(Request $request)
    {
        $request->validate([
            'purchase_code' => 'required',
            'email' => 'bail|required|email'
        ], [
            'purchase_code.required' => 'Purchase code field is required',
            'email.required' => 'Customer email field is required',
            'email.email' => 'Customer email field is must a valid email'
        ]);

        $response = Http::acceptJson()
            ->withOptions(['verify' => env('IS_LOCAL', false) == false ? true : false])
            ->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/active', [
                'app' => config('app.app_code'),
                'is_localhost' => env('IS_LOCAL', false),
                'type' => 1,
                'email' => $request->email,
                'purchase_code' => $request->purchase_code,
                'version' => config('app.build_version'),
                'url' => $request->fullUrl(),
                'app_url' => env('APP_URL'),
            ]);

        if ($response->successful()) {
            $data = $response->object();
            if ($data->status === 'success') {
                Artisan::call('migrate', [
                    '--force' => true
                ]);

                $data = json_decode($data->data->data);
                // Log::info($data);
                foreach ($data as $d) {
                    if (!Artisan::call($d)) {
                        break;
                    }
                }

                $installedLogFile = storage_path('installed');
                if (file_exists($installedLogFile)) {
                    $data = json_decode(file_get_contents($installedLogFile));
                    if (!is_null($data) && isset($data->d)) {
                        $data->u = date('ymdhis');
                    } else {
                        $data = [
                            'd' => base64_encode(get_domain_name(request()->fullUrl())),
                            'i' => date('ymdhis'),
                            'p' => base64_encode($request->purchase_code),
                            'u' => date('ymdhis'),
                        ];
                    }

                    file_put_contents($installedLogFile, json_encode($data));
                    Artisan::call('storage:link');
                }
            } else {
                return Redirect::back()->withErrors(['purchase_code' => $data->message]);
            }
        } else {
            return Redirect::back()->withErrors(['purchase_code' => 'Something went wrong with your purchase key.']);
        }

        return redirect()->route('frontend');
    }

    public function versionFileUpdate(Request $request)
    {
        if (isAddonInstalled('PROTYSAAS') > 0 && auth()->user()->role == USER_ROLE_OWNER) {
            abort(403);
        }

        $data['pageTitle'] = __('Version Update');
        $data['subNavVersionUpdateActiveClass'] = 'mm-active';
        $apiResponse = Http::acceptJson()
            ->withOptions(['verify' => env('IS_LOCAL', false) == false ? true : false])
            ->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/ad', [
                'app' => config('app.app_code'),
                'is_localhost' => env('IS_LOCAL', false),
            ]);

        if ($apiResponse->successful()) {
            $responseData = $apiResponse->object();
            $data['latestVersion'] = $responseData->data->cv;
            $data['latestBuildVersion'] = $responseData->data->bv;
            $data['addons'] = $responseData->data->addons;
        } else {
            return back()->with('error', __('Something went wrong.'));
        }

        $path = storage_path('app/source-code.zip');
        if (file_exists($path)) {
            $data['uploadedFile'] = 'source-code.zip';
        } else {
            $data['uploadedFile'] = '';
        }

        try {
            $results = DB::select(DB::raw('select version()'));
            $data['mysql_version'] = $results[0]->{'version()'};
            $data['databaseType'] = 'MySQL Version';

            if (str_contains($data['mysql_version'], 'Maria')) {
                $data['databaseType'] = 'MariaDB Version';
            }
        } catch (Exception $e) {
            $data['mysql_version'] = null;
        }


        return view('version_update.create', $data);
    }

    public function versionFileUpdateStore(Request $request)
    {
        $request->validate([
            'update_file' => 'bail|required|mimes:zip'
        ]);
        set_time_limit(1200);
        $path = storage_path('app/source-code.zip');

        if (file_exists($path)) {
            File::delete($path);
        }

        try {
            $request->update_file->storeAs('/', 'source-code.zip');
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function executeUpdate()
    {
        set_time_limit(1200);
        $path = storage_path('app/source-code.zip');
        $demoPath = storage_path('app/updates');

        $response['success'] = false;
        $response['message'] = 'File not exist on storage!';

        $this->logger->log('Update Start', '==========');
        if (file_exists($path)) {
            $this->logger->log('File Found', 'Success');
            $zip = new ZipArchive;

            if (is_dir($demoPath)) {
                $this->logger->log('Updates directory', 'exist');
                $this->logger->log('Updates directory', 'deleting');
                File::deleteDirectory($demoPath);
                $this->logger->log('Updates directory', 'deleted');
            }

            $this->logger->log('Updates directory', 'creating');
            File::makeDirectory($demoPath, 0777, true, true);
            $this->logger->log('Updates directory', 'created');

            $this->logger->log('Zip', 'opening');
            $res = $zip->open($path);

            if ($res === true) {
                $this->logger->log('Zip', 'Open successfully');
                try {
                    $this->logger->log('Zip Extracting', 'Start');
                    $res = $zip->extractTo($demoPath);
                    $this->logger->log('Zip Extracting', 'END');
                    $this->logger->log('Get update note', 'START');
                    $versionFile = file_get_contents($demoPath . DIRECTORY_SEPARATOR . 'update_note.json');
                    $updateNote = json_decode($versionFile);
                    $this->logger->log('Get update note', 'END');
                    $this->logger->log('Get Build Version from update note', 'START');
                    $codeVersion = $updateNote->build_version;
                    $this->logger->log('Get Build Version from update note', 'END');
                    $this->logger->log('Get Root Path from update note', 'START');
                    $codeRootPath = $updateNote->root_path;
                    $this->logger->log('Get Root Path from update note', 'END');
                    $this->logger->log('Get current version', 'START');
                    $currentVersion = getCustomerCurrentBuildVersion();
                    $this->logger->log('Get current version', 'END');
                    $this->logger->log('Checking if updatable version from api', 'START');
                    $apiResponse = Http::acceptJson()
                        ->withOptions(['verify' => env('IS_LOCAL', false) == false ? true : false])
                        ->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/glv', [
                            'app' => config('app.app_code'),
                            'is_localhost' => env('IS_LOCAL', false),
                        ]);
                    $this->logger->log('Checking if updatable version from api', 'END');

                    if ($apiResponse->successful()) {
                        $this->logger->log('Response', 'Success');
                        $data = $apiResponse->object();
                        $this->logger->log('Response Data', json_encode($data));
                        $latestVersion = $data->data->bv;
                        if ($data->status === 'success') {
                            $this->logger->log('Response status', 'Success');
                            $this->logger->log('Checking if updatable code', 'START');
                            if ($latestVersion == $codeVersion && $codeVersion > $currentVersion) {
                                $this->logger->log('Checking if updatable code', 'True');
                                $this->logger->log('Move file', 'START');

                                $allMoveFilePath = (array)($updateNote->code_path);
                                foreach ($allMoveFilePath as $filePath => $type) {
                                    $this->logger->log('Move file', 'Start ' . $demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath . ' to ' . base_path($filePath));
                                    if ($type == 'file') {
                                        File::copy($demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath, base_path($filePath));
                                    } else {
                                        File::copyDirectory($demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath, base_path($filePath));
                                    }
                                    $this->logger->log('Move file', 'END ' . $demoPath . DIRECTORY_SEPARATOR . $codeRootPath . DIRECTORY_SEPARATOR . $filePath . ' to ' . base_path($filePath));
                                }
                                $response['success'] = true;
                                $response['message'] = 'Successfully done';
                                $this->logger->log('Move file', 'Done');
                            } else {
                                $response['message'] = 'Your code is not up to date';
                                $this->logger->log('Version', 'Not matched');
                            }
                        } else {
                            $response['message'] = $data->message;
                            $this->logger->log('Response Status', 'Failed');
                        }
                    } else {
                        $data = $apiResponse->object();
                        $response['message'] = $data['message'];
                        $this->logger->log('Response', 'Failed');
                    }

                    $this->logger->log('Demo extracted path', 'Deleting');
                    File::deleteDirectory($demoPath);

                    $zipPath = storage_path('app/source-code.zip');
                    if (file_exists($zipPath)) {
                        File::delete($zipPath);
                    }
                    $this->logger->log('Demo extracted path', 'Deleted');
                } catch (Exception $e) {
                    Log::info($e->getMessage());
                    $response['message'] = $e->getMessage();
                    $this->logger->log('Exception', $e->getMessage());
                }
                $zip->close();
            } else {
                $this->logger->log('Zip', 'Open failed');
            }
        }

        $this->logger->log('', '===============Update END==============');

        return $response;
    }

    public function versionUpdateExecute()
    {
        $response = $this->executeUpdate();
        if ($response['success'] == true) {
            return back();
        }
        return back()->with('error', json_encode($response['message']));
    }

    public function versionFileUpdateDelete()
    {
        $path = storage_path('app/source-code.zip');

        if (file_exists($path)) {
            File::delete($path);
        }
    }

    public function versionCheck()
    {
        return ['App build version' => config('app.build_version'), 'Customer current build version' => getCustomerCurrentBuildVersion()];
    }
}
