<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class AddonUpdateController extends Controller
{
    use ResponseTrait;
    protected $logger;
    public function __construct()
    {
        $this->logger = new Logger(storage_path('logs/addon.log'));
    }

    public function addonSaasDetails($code)
    {
        $data['subNavVersionUpdateActiveClass'] = 'mm-active';
        $apiResponse = Http::acceptJson()
            ->withOptions(['verify' => env('IS_LOCAL', false) == false ? true : false])
            ->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/glv', [
                'app' => $code,
                'is_localhost' => env('IS_LOCAL', false),
                'app_build_version' => getCustomerCurrentBuildVersion(),
                'addon_build_version' => getCustomerAddonBuildVersion($code),
            ]);

        if ($apiResponse->successful()) {
            $responseData = $apiResponse->object();
            // check update version
            if ($responseData->data->pbv > getCustomerCurrentBuildVersion()) {
                return back()->with('error', __('Please Update Main App First'));
            }
            $data['pageTitle'] = $responseData->data->title . " " . __('Install');
            $data['latestVersion'] = $responseData->data->cv;
            $data['licenseStatus'] = $responseData->data->license_status;
            $data['buildVersion'] = $responseData->data->bv;
            $data['requiredVersion'] = $responseData->data->prv;
            $data['codecanyon_url'] = $responseData->data->codecanyon_url;
            $data['code'] = $code;

            $data['appLatestVersion'] = $responseData->data->pcv;
            $data['appBuildVersion'] = $responseData->data->pbv;
        } else {
            return back()->with('error', __('Something went wrong.'));
        }

        $path = storage_path('app/addons/' . $code . '.zip');
        if (file_exists($path)) {
            $data['uploadedFile'] = $code . '.zip';
        } else {
            $data['uploadedFile'] = '';
        }

        return view('version_update.addon.saas-multi-owner', $data);
    }

    public function addonSaasFileStore(Request $request)
    {
        $request->validate([
            'update_file' => 'bail|required|mimes:zip'
        ]);
        set_time_limit(1200);
        $path = storage_path('app/addons/' . $request->code . '.zip');

        if (file_exists($path)) {
            File::delete($path);
        }

        try {
            $request->update_file->storeAs('addons/', $request->code . '.zip');
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function addonSaasFileExecute(Request $request)
    {
        if ($request->licenseStatus == 1) {
            $request->validate([
                'email' => 'required',
                'purchase_code' => 'required'
            ]);
        }

        $purchase_code = $request->purchase_code;
        $code = $request->code;
        try {
            $returnResponse = $this->addonSaasFileExecuteUpdate($code, $purchase_code, $request->email, $request->fullUrl());
            if ($returnResponse['success'] == true) {
                Auth::logout();
                return $this->success([], __('Addon Installed Successfully'));;
            }
            return $this->error([], json_encode($returnResponse['message']));
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function addonSaasFileExecuteUpdate($code, $purchase_code, $email, $fullUrl)
    {
        set_time_limit(1200);
        $path = storage_path('app/addons/' . $code . '.zip');
        $demoPath = storage_path('app/addons/' . $code);
        $returnResponse['success'] = false;
        $returnResponse['message'] = 'File not exist on storage!';

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

                    $this->logger->log('Checking Purchase key', 'START');
                    $response = Http::acceptJson()
                        ->withOptions(['verify' => env('IS_LOCAL', false) == false ? true : false])
                        ->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/active', [
                            'app' => $code,
                            'is_localhost' => env('IS_LOCAL', false),
                            'type' => 1,
                            'email' => $email,
                            'purchase_code' => $purchase_code,
                            'version' => $codeVersion,
                            'url' => $fullUrl,
                            'app_url' => env('APP_URL'),
                        ]);
                    $this->logger->log('Checking Purchase key', 'Response');
                    if ($response->successful()) {
                        $versionResponseData = $response->object();
                        $this->logger->log('Checking Purchase key', 'Response Success');
                        $this->logger->log('Checking Purchase key Response Data', json_encode($versionResponseData));
                        // dd($data);
                        if ($versionResponseData->status === 'success') {
                            $this->logger->log('Checking Purchase key', 'Purchase key valid');
                            $this->logger->log('Get Root Path from update note', 'START');
                            $codeRootPath = $updateNote->root_path;
                            $this->logger->log('Get Root Path from update note', 'END');
                            $this->logger->log('Get current version', 'START');
                            // $currentVersion = getCustomerAddonBuildVersion($code);
                            $this->logger->log('Get current version', 'END');
                            $this->logger->log('Checking if updatable version from api', 'START');
                            $apiResponse = Http::acceptJson()
                                ->withOptions(['verify' => env('IS_LOCAL', false) == false ? true : false])
                                ->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/glv', [
                                    'app' => $code,
                                    'is_localhost' => env('IS_LOCAL', false),
                                ]);
                            $this->logger->log('Checking if updatable version from api', 'END');

                            if ($apiResponse->successful()) {
                                $this->logger->log('Response', 'Success');
                                $data = $apiResponse->object();
                                $this->logger->log('Response Data', json_encode($data));
                                if ($data->status === 'success') {
                                    $latestVersion = $data->data->bv;
                                    $this->logger->log('Response status', 'Success');
                                    $this->logger->log('Checking if updatable code', 'START');
                                    if ($latestVersion == $codeVersion) {
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
                                        $returnResponse['success'] = true;
                                        $returnResponse['message'] = 'Successfully done';
                                        $this->logger->log('Move file', 'Done');

                                        Artisan::call('view:clear');
                                        Artisan::call('route:clear');
                                        Artisan::call('config:clear');
                                        Artisan::call('cache:clear');

                                        $this->logger->log('Migration', 'Start');
                                        Artisan::call('migrate', [
                                            '--force' => true
                                        ]);
                                        $this->logger->log('Migration', 'END');
                                        $data = json_decode($versionResponseData->data->data);
                                        $this->logger->log('Command list', $data);
                                        foreach ($data as $d) {
                                            $this->logger->log('Command run ' . $d, 'START');
                                            if (!Artisan::call($d)) {
                                                $this->logger->log('Command run ' . $d, 'FAILED');
                                                $returnResponse['success'] = false;
                                                throw new Exception('Something went wrong. Please try again');
                                                break;
                                            }
                                            $this->logger->log('Command run ' . $d, 'SUCCESS');
                                        }

                                        Artisan::call('view:clear');
                                        Artisan::call('route:clear');
                                        Artisan::call('config:clear');
                                        Artisan::call('cache:clear');
                                    } else {
                                        $returnResponse['message'] = 'Your code is not up to date';
                                        $this->logger->log('Version', 'Not matched');
                                    }
                                } else {
                                    $returnResponse['message'] = $data->message;
                                    $this->logger->log('Response Status', 'Failed');
                                }
                            } else {
                                $data = $apiResponse->object();
                                $returnResponse['message'] = $data['message'];
                                $this->logger->log('Response', 'Failed');
                            }

                            $this->logger->log('Demo extracted path', 'Deleting');
                            File::deleteDirectory($demoPath);
                            $this->logger->log('Demo extracted path', 'Deleted');

                            $this->logger->log('Extracted addon zip file', 'Deleting');
                            if (file_exists($path)) {
                                File::delete($path);
                            }
                            $this->logger->log('Extracted addon zip file', 'Deleted');
                        } else {
                            // return $this->error([], $data->message);
                            $returnResponse['message'] = $versionResponseData->message;
                            $this->logger->log('Checking Purchase key', $versionResponseData->message);
                        }
                    } else {
                        $data = $response->object();
                        $this->logger->log('Checking Purchase key', $data->message);
                        $returnResponse['message'] = 'Something went wrong with your purchase key.';
                        // return $this->error([], );
                    }
                } catch (Exception $e) {
                    $returnResponse['message'] = $e->getMessage();
                    $this->logger->log('Exception', $e->getMessage());
                }
                $zip->close();
            } else {
                $this->logger->log('Zip', 'Open failed');
            }
        }

        $this->logger->log('', '===============Update END==============');

        return $returnResponse;
    }

    public function addonSaasFileDelete($code)
    {
        $path = storage_path('app/addons/' . $code . '.zip');

        if (file_exists($path)) {
            File::delete($path);
        }
    }
}
