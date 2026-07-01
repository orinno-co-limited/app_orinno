<?php

use App\Models\CorePage;
use App\Models\Currency;
use App\Models\FileManager;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceRecurringSetting;
use App\Models\Language;
use App\Models\Maintainer;
use App\Models\Notification;
use App\Models\OwnerPackage;
use App\Models\Package;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Setting;
use App\Models\TaxSetting;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

function getOption($option_key, $default = '')
{
    $system_settings = config('settings');
    if ($option_key && isset($system_settings[$option_key])) {
        return $system_settings[$option_key];
    } else {
        return $default;
    }
}

if (!function_exists('getSlug')) {
    function getSlug($text)
    {
        if ($text) {
            $text = str_replace("\n", " ", $text);
            $data = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $text);
            $slug = preg_replace("/[\/_|+ -]+/", "-", $data);
            return $slug;
        }
        return '';
    }
}

function number_parser($value)
{
    return (float)str_replace(',', '', number_format(($value), 2));
}

function assetUrl($path)
{
    if ($path != '/') {
        return asset('storage/' . $path);
    }
    return asset('assets/images/no-image.jpg');
}

if (!function_exists('gatewaySettings')) {
    function gatewaySettings()
    {
        $gatewaySettings = [
            "paypal" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Client ID", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 1]
            ],
            "stripe" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret Key", "name" => "secret", "is_show" => 0]
            ],
            "razorpay" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 1]
            ],
            "instamojo" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Api Key", "name" => "key", "is_show" => 1],
                ["label" => "Auth Token", "name" => "secret", "is_show" => 1]
            ],
            "mollie" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Mollie Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 0]
            ],
            "paystack" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Secret Key", "name" => "key", "is_show" => 1],
                ["label" => "Public Key", "name" => "secret", "is_show" => 0]
            ],
            "mercadopago" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Client ID", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
            ],
            "sslcommerz" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Store ID", "name" => "key", "is_show" => 1],
                ["label" => "Store Password", "name" => "secret", "is_show" => 1]
            ],
            "flutterwave" => [
                ["label" => "Hash", "name" => "url", "is_show" => 1],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
            ],
            "coinbase" => [
                ["label" => "Hash", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
            ],
            "binance" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Client ID", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
            ],
            "bitpay" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
            ],
            "iyzipay" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 1]
            ],
            "payhere" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
                ["label" => "Merchant Secret", "name" => "secret", "is_show" => 1]
            ],
            "maxicash" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
                ["label" => "Password", "name" => "secret", "is_show" => 1]
            ],
            "paytm" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 1],
                ["label" => "Merchant Key", "name" => "key", "is_show" => 1],
                ["label" => "Merchant ID", "name" => "secret", "is_show" => 1]
            ],
            "zitopay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Merchant ID", "name" => "secret", "is_show" => 0]
            ],
            "cinetpay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 1],
                ["label" => "Site ID", "name" => "secret", "is_show" => 1]
            ],
            "voguepay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
                ["label" => "Merchant ID", "name" => "secret", "is_show" => 0]
            ],
            "toyyibpay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Secret Key", "name" => "key", "is_show" => 1],
                ["label" => "Category Code", "name" => "secret", "is_show" => 1]
            ],
            "paymob" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 1],
                ["label" => "Integration ID", "name" => "secret", "is_show" => 1]
            ],
            "alipay" => [
                ["label" => "APP ID", "name" => "url", "is_show" => 1],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Private Key", "name" => "secret", "is_show" => 1]
            ],
            "authorize" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Login ID", "name" => "key", "is_show" => 1],
                ["label" => "Transaction Key", "name" => "secret", "is_show" => 1]
            ],
            "xendit" => [
                ["label" => "APP ID", "name" => "url", "is_show" => 0],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 0]
            ],
            "paddle" => [
                [ "label" => "Url", "name" => "url", "is_show" => 1],
                [ "label" => "Public Key", "name" => "key", "is_show" => 1],
                [ "label" => "Secret", "name" => "secret", "is_show" => 0]
            ],
            "bank" => [
                ["label" => "Hash", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 0],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
            ],
            "cash" => [
                ["label" => "Hash", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 0],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
            ]
        ];

        return json_encode($gatewaySettings);
    }
}

// function getSettingImage($option_key)
// {
//     try {
//         $system_settings = config('settings');
//         if ($option_key && isset($system_settings[$option_key])) {
//             $fileManager = FileManager::find($system_settings[$option_key]);
//             $destinationPath = 'files/Setting' . '/' . $fileManager->file_name;
//             if (Storage::disk(config('app.STORAGE_DRIVER'))->exists($destinationPath)) {
//                 if (config('app.STORAGE_DRIVER') == "s3") {
//                     $s3 = Storage::disk(config('app.STORAGE_DRIVER'));
//                     return $s3->url($destinationPath);
//                 }
//                 return asset('storage/' . $destinationPath);
//             }
//         } else {
//             return asset('assets/images/users/empty-user.jpg');
//         }
//     } catch (\Exception $e) {
//         return asset('assets/images/users/empty-user.jpg');
//     }
// }

function getSettingImage($option_key)
{
    try {
        if (!$option_key) {
            return asset('assets/images/users/empty-user.jpg');
        }

        // 1) via Setting → FileManager
        $setting = Setting::where('option_key', $option_key)->first();
        if ($setting && $setting->fileAttach) {
            return $setting->fileAttach->FileUrl;
        }

        // 2) backward-compat: config('settings')['app_logo'] == file_managers.id
        $system_settings = config('settings');
        if (is_array($system_settings) && isset($system_settings[$option_key])) {
            $fileManager = FileManager::find($system_settings[$option_key]);
            if ($fileManager) {
                return $fileManager->FileUrl;
            }
        }

        return asset('assets/images/users/empty-user.jpg');
    } catch (\Exception $e) {
        return asset('assets/images/users/empty-user.jpg');
    }
}

function settingImageStoreUpdate($option_id, $requestFile, $name)
{
    $new_file = FileManager::where('origin_type', 'App\Models\Setting')->where('origin_id', $option_id)->first();

    if ($new_file) {
        $new_file->removeFile();
        $upload = $new_file->updateUpload($new_file->id, 'Setting', $requestFile, $name);
    } else {
        $new_file = new FileManager();
        $upload = $new_file->upload('Setting', $requestFile, $name);
    }

    if ($upload['status']) {
        $upload['file']->origin_id = $option_id;
        $upload['file']->origin_type = "App\Models\Setting";
        $upload['file']->save();
        return $upload['file']->id;
    } else {
        throw new Exception($upload['message']);
    }
}

function getErrorMessage($e, $customMsg = null)
{
    if ($customMsg != null) {
        return $customMsg;
    }
    if (env('APP_DEBUG')) {
        return $e->getMessage() . $e->getLine();
    } else {
        return __(SOMETHING_WENT_WRONG);
    }
}

function set_local_timezone($timezone)
{
    config(['app.timezone' => @$timezone] ?? 'UTC');
    date_default_timezone_set(@$timezone ?? 'UTC');
}

function getFileUrl($folderName, $fileName)
{
    if ($fileName == '' || $folderName == '') {
        return asset('assets/images/no-image.jpg');
    }
    $destinationPath = $folderName . '/' . $fileName;
    if (Storage::disk(config('app.STORAGE_DRIVER'))->exists($destinationPath)) {
        if (config('app.STORAGE_DRIVER') != "public") {
            $s3 = Storage::disk(config('app.STORAGE_DRIVER'));
            return $s3->url($destinationPath);
        }
        if ($destinationPath != '/') {
            return asset('storage/' . $destinationPath);
        }
    }

    return asset('assets/images/no-image.jpg');
}

function copyFolder($source, $destination)
{
    if (is_dir($source)) {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true); // Create the destination directory if it doesn't exist
        }

        $dir = opendir($source);

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $src = $source . '/' . $file;
                $dest = $destination . '/' . $file;

                if (is_dir($src)) {
                    // If it's a directory, recursively call the function
                    copyFolder($src, $dest);
                } else {
                    // If it's a file, use copy() to copy it
                    copy($src, $dest);
                }
            }
        }

        closedir($dir);
    } else {
        copy($source, $destination);
    }
}


if (!function_exists('getCityById')) {
    function getCityById($city_id)
    {
        $cities_file = public_path('file/cities.csv');
        $cityArr = csvToArray($cities_file);
        foreach ($cityArr as $city) {
            if ($city['id'] == $city_id) {
                $result = array(
                    'id' => $city['id'],
                    'name' => $city['name'],
                    'state_id' => $city['state_id'],
                );
                return $result;
            }
        }
        return '';
    }
}

if (!function_exists('getStateById')) {
    function getStateById($state_id)
    {
        $states_file = public_path('file/states.csv');
        $stateArr = csvToArray($states_file);
        foreach ($stateArr as $state) {
            if ($state['id'] == $state_id) {
                $result = array(
                    'id' => $state['id'],
                    'name' => $state['name'],
                    'country_id' => $state['country_id'],
                );
                return $result;
            }
        }
        return '';
    }
}

if (!function_exists('getCountryById')) {
    function getCountryById($country_id)
    {
        $states_file = public_path('file/countries.csv');
        $countryArr = csvToArray($states_file);
        foreach ($countryArr as $country) {
            if ($country['id'] == $country_id) {
                $result = array(
                    'id' => $country['id'],
                    'name' => $country['country_name'],
                    'sortname' => $country['sortname'],
                );
                return $result;
            }
        }
        return '';
    }
}

if (!function_exists('csvToArray')) {
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}

function convertToReadableSize($size)
{
    $base = log($size) / log(1024);
    $suffix = array("", "KB", "MB", "GB", "TB");
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

function getSystemCurrency()
{
    return Currency::where('current_currency', ACTIVE)
        ->select(['currency_code', 'symbol', 'currency_placement', 'current_currency'])
        ->first();
}

function getCurrencySymbol()
{
    $currency = Currency::where('current_currency', ACTIVE)->first();
    if ($currency) {
        $symbol = $currency->symbol . ' ';
        return $symbol;
    }
    return '';
}

function getCurrencyPlacement()
{
    $currency = Currency::where('current_currency', ACTIVE)->first();
    $placement = 'before';
    if ($currency) {
        $placement = $currency->currency_placement;
        return $placement;
    }

    return $placement;
}

function currencyPrice($price)
{
    if ($price == null) {
        return 0;
    }
    if (getCurrencyPlacement() == 'after')
        return number_format($price, 2) . ' ' . getCurrencySymbol();
    else {
        return getCurrencySymbol() . number_format($price, 2);
    }
}

function setEnvironmentValue($envKey, $envValue)
{
    try {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        if ($keyPosition) {
            if (PHP_OS_FAMILY === 'Windows') {
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
            } else {
                $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
            }
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
            $envValue = str_replace(chr(92), "\\\\", $envValue);
            $envValue = str_replace('"', '\"', $envValue);
            $newLine = "{$envKey}=\"{$envValue}\"";
            if ($oldLine != $newLine) {
                $str = str_replace($oldLine, $newLine, $str);
                $str = substr($str, 0, -1);
                $fp = fopen($envFile, 'w');
                fwrite($fp, $str);
                fclose($fp);
            }
        } else if (strtoupper($envKey) == $envKey) {
            $envValue = str_replace(chr(92), "\\\\", $envValue);
            $envValue = str_replace('"', '\"', $envValue);
            $newLine = "{$envKey}=\"{$envValue}\"\n";
            $str .= $newLine;
            $str = substr($str, 0, -1);
            $fp = fopen($envFile, 'w');
            fwrite($fp, $str);
            fclose($fp);
        }
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

if (!function_exists('languages')) {
    function languages()
    {
        $data = Language::where('status', 1)->get();
        if ($data) {
            return $data;
        }
        return [];
    }
}
if (!function_exists('languageLocale')) {
    function languageLocale($locale)
    {
        $data = Language::where('code', $locale)->first();
        if ($data) {
            return $data->code;
        }
        return 'en';
    }
}

function selectedLanguage()
{
    $language = Language::where('code', session()->get('local'))->first();
    if (!$language) {
        $language = Language::first();
        if ($language) {
            $ln = $language->code;
            session(['local' => $ln]);
            Carbon::setLocale($ln);
            App::setLocale(session()->get('local'));
        }
    }

    return $language;
}

function appLanguages()
{
    $languages = Language::where('status', 1)->get();
    return $languages?->makeHidden(['created_at', 'updated_at', 'deleted_at']);
}

function propertyTotalRoom($property_id)
{
    if ($property_id) {
        return PropertyUnit::where('property_id', $property_id)->sum('bedroom');
    }
    return 0;
}

function invoiceItemTotalAmount($invoice_id)
{
    return InvoiceItem::where('invoice_id', $invoice_id)->sum('amount');
}

if (!function_exists('getLayout')) {
    function getLayout()
    {
        $output = [
            USER_ROLE_ADMIN => 'admin',
            USER_ROLE_OWNER => 'owner',
            USER_ROLE_TENANT => 'tenant',
            USER_ROLE_MAINTAINER => 'maintainer',
            USER_ROLE_TEAM_MEMBER => 'owner',
        ];

        return $output[auth()->user()->role];
    }
}

if (!function_exists('updateEnv')) {
    function updateEnv($values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $str .= "\n";
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}=" . json_encode($envValue) . "\n";
                } else {
                    Log::info("{$envKey}=" . json_encode($envValue));
                    $str = str_replace($oldLine, "{$envKey}=" . json_encode($envValue), $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) {
            return false;
        } else {
            return true;
        }
    }
}

function addNotification($title, $body = null, $url = null, $image = null, $user_id = null, $sender_id = null)
{
    $notification = new Notification();
    $notification->title = $title;
    $notification->body = $body;
    $notification->url = $url;
    $notification->image = $image;
    $notification->user_id = $user_id;
    $notification->sender_id = $sender_id;
    $notification->save();
}

function getNotification($user_id)
{
    $fetchNotification = Notification::join('users', 'users.id', '=', 'notifications.sender_id')
        ->leftJoin('file_managers', function ($join) {
            $join->on('file_managers.origin_id', '=', 'notifications.sender_id')
                ->where('file_managers.origin_type', '=', 'App\Models\User');
        })
        ->select('notifications.*', 'users.first_name', 'users.last_name', 'file_managers.file_name', 'file_managers.folder_name')
        ->where(function ($query) use ($user_id) {
            $query->where('notifications.user_id', $user_id);
            // ->orWhere('notifications.user_id', null);
        })
        ->latest()
        ->get();

    return $fetchNotification;
}

function getNotificationLimit($user_id)
{
    $fetchNotification = Notification::join('users', 'users.id', '=', 'notifications.sender_id')
        ->leftJoin('file_managers', function ($join) {
            $join->on('file_managers.origin_id', '=', 'notifications.sender_id')
                ->where('file_managers.origin_type', '=', 'App\Models\User');
        })
        ->select('notifications.*', 'users.first_name', 'users.last_name', 'file_managers.file_name', 'file_managers.folder_name')
        ->where(function ($query) use ($user_id) {
            $query->where('notifications.user_id', $user_id);
            // ->orWhere('notifications.user_id', null);
        })
        ->where('is_seen', DEACTIVATE)
        ->take(10)
        ->latest()
        ->get();

    return $fetchNotification;
}

if (!function_exists('taxSetting')) {
    function taxSetting($userId = null)
    {
        $userId = isset($userId) ? $userId : auth()->id();
        $tax = TaxSetting::where('owner_user_id', $userId)->first();
        if (is_null($tax)) {
            $tax = TaxSetting::updateOrCreate(['owner_user_id' => auth()->id()], [
                'owner_user_id' => auth()->id(),
            ]);
        }
        return $tax;
    }
}

if (!function_exists('getCustomerCurrentBuildVersion')) {
    function getCustomerCurrentBuildVersion()
    {
        $buildVersion = getOption('build_version');

        if (is_null($buildVersion)) {
            return 1;
        }

        return (int)$buildVersion;
    }
}

if (!function_exists('getCustomerAddonBuildVersion')) {
    function getCustomerAddonBuildVersion($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        if (is_null($buildVersion)) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('isAddonInstalled')) {
    function isAddonInstalled($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        $codeBuildVersion = getAddonCodeBuildVersion($code);
        if (is_null($buildVersion) || $codeBuildVersion == 0) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerAddonCurrentVersion')) {
    function setCustomerAddonCurrentVersion($code)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_current_version']);
        if (getAddonCodeCurrentVersion($code)) {
            $option->option_value = getAddonCodeCurrentVersion($code);
            $option->save();
        }
    }
}

if (!function_exists('setCustomerAddonBuildVersion')) {
    function setCustomerAddonBuildVersion($code, $version)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('setCustomerBuildVersion')) {
    function setCustomerBuildVersion($version)
    {
        $option = Setting::firstOrCreate(['option_key' => 'build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('setCustomerCurrentVersion')) {
    function setCustomerCurrentVersion()
    {
        $option = Setting::firstOrCreate(['option_key' => 'current_version']);
        $option->option_value = config('app.current_version');
        $option->save();
    }
}

if (!function_exists('setOwnerGateway')) {
    function setOwnerGateway($userId)
    {
        $data = [
            ['owner_user_id' => $userId, 'title' => 'Paypal', 'slug' => 'paypal', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/paypal.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Stripe', 'slug' => 'stripe', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/stripe.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Razorpay', 'slug' => 'razorpay', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/razorpay.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Instamojo', 'slug' => 'instamojo', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/instamojo.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Mollie', 'slug' => 'mollie', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/mollie.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Paystack', 'slug' => 'paystack', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/paystack.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/sslcommerz.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Flutterwave', 'slug' => 'flutterwave', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/flutterwave.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Mercadopago', 'slug' => 'mercadopago', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/mercadopago.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Bank', 'slug' => 'bank', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/bank.jpg'],
            ['owner_user_id' => $userId, 'title' => 'Cash', 'slug' => 'cash', 'status' => DEACTIVATE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => '', 'image' => 'assets/images/gateway-icon/cash.jpg'],
        ];
        Gateway::insert($data);
    }
}

if (!function_exists('setUserPackage')) {
    function setUserPackage($userId, $package, $duration, $quantity = 1, $orderId = NULL)
    {
        OwnerPackage::where(['user_id' => $userId])->whereIn('status', [ACTIVE])->update(['status' => DEACTIVATE]);
        OwnerPackage::create([
            'user_id' => $userId,
            'package_id' => $package->id,
            'package_type' => $package->type ?? PACKAGE_TYPE_UNIT,
            'quantity' => $quantity,
            'name' => $package->name,
            'max_maintainer' => $package->max_maintainer,
            'max_property' => $package->max_property,
            'max_unit' => $package->max_unit,
            'max_tenant' => $package->max_tenant,
            'max_invoice' => $package->max_invoice,
            'max_auto_invoice' => $package->max_auto_invoice,
            'ticket_support' => $package->ticket_support,
            'notice_support' => $package->notice_support,
            'monthly_price' => $package->monthly_price,
            'yearly_price' => $package->yearly_price,
            'per_monthly_price' => $package->per_monthly_price,
            'per_yearly_price' => $package->per_yearly_price,
            'order_id' => $orderId,
            'is_trail' => $package->is_trail,
            'start_date' => now(),
            'end_date' => Carbon::now()->addDays($duration),
            'status' => ACTIVE,
        ]);
    }
}

if (!function_exists('corePages')) {
    function corePages($take = null)
    {
        return CorePage::where('status', ACTIVE)->take($take ?? 4)->get();
    }
}

if (!function_exists('getAddonCodeCurrentVersion')) {
    function getAddonCodeCurrentVersion($appCode)
    {
        Artisan::call("config:clear");
        if ($appCode == 'PROTYSAAS') {
            return config('addon.PROTYSAAS.current_version', 0);
        } elseif ($appCode == 'PROTYSMS') {
            return config('smsmail.PROTYSMS.current_version', 0);
        } elseif ($appCode == 'PROTYAGREEMENT') {
            return config('agreement.PROTYAGREEMENT.current_version', 0);
        } elseif ($appCode == 'PROTYTENANCY') {
            return config('tenancy.PROTYTENANCY.current_version', 0);
        } elseif ($appCode == 'PROTYLISTING') {
            return config('flutter.PROTYLISTING.current_version', 0);
        }
    }
}

if (!function_exists('getAddonCodeBuildVersion')) {
    function getAddonCodeBuildVersion($appCode)
    {
        Artisan::call("config:clear");
        if ($appCode == 'PROTYSAAS') {
            return config('addon.PROTYSAAS.build_version', 0);
        } elseif ($appCode == 'PROTYSMS') {
            return config('smsmail.PROTYSMS.build_version', 0);
        } elseif ($appCode == 'PROTYAGREEMENT') {
            return config('agreement.PROTYAGREEMENT.build_version', 0);
        } elseif ($appCode == 'PROTYTENANCY') {
            return config('tenancy.PROTYTENANCY.build_version', 0);
        } elseif ($appCode == 'PROTYLISTING') {
            return config('listing.PROTYLISTING.build_version', 0);
        }
    }
}

function getEmailTemplate($body, $customizedFieldsArray = [])
{
    if ($body) {
        $body = $body;
        if ($customizedFieldsArray) {
            foreach (emailTemplateFields() as $key => $item) {
                if (isset($customizedFieldsArray[$key])) {
                    $body = str_replace($key, $customizedFieldsArray[$key], $body);
                }
            }
        }
        return $body;
    }
    return '';
}

if (!function_exists('get_domain_name')) {
    function get_domain_name($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }
}

if (!function_exists('reviewStar')) {
    function reviewStar($star)
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i > $star) {
                $html .= '<li class="d-flex"><i class="fa-regular fa-star"></i></li>';
            } else {
                $html .= '<li class="d-flex"><i class="fa-solid fa-star"></i></li>';
            }
        }
        return $html;
    }
}

if (!function_exists('ownerCurrentPackage')) {
    function ownerCurrentPackage($userId)
    {
        return OwnerPackage::query()
            ->where('status', ACTIVE)
            ->where('user_id', $userId)
            ->whereDate('end_date', '>=', now()->toDateTimeString())
            ->first();
    }
}

if (!function_exists('getOwnerLimit')) {
    function getOwnerLimit($type, $userId = NULL)
    {
        if (isAddonInstalled('PROTYSAAS') < 1) {
            return true;
        }
        $userId = is_null($userId) ? getOwnerUserId() : $userId;
        $ownerPlan = OwnerPackage::where('status', ACTIVE)->where('user_id', $userId)->whereDate('end_date', '>=', now()->toDateTimeString())->first();

        // custom package limit check
        //        if (!is_null($ownerPlan)) {
        //            if (in_array($ownerPlan->package_type, [PACKAGE_TYPE_PROPERTY, PACKAGE_TYPE_UNIT, PACKAGE_TYPE_TENANT])) {
        //                $quantity = $ownerPlan->quantity;
        //                if ($type == RULES_INVOICE && $ownerPlan->max_invoice != -1) {
        //                    $limit = $ownerPlan->max_invoice;
        //                    $used = Invoice::where('owner_user_id', $userId)->count();
        //                    $remain = $limit - $used;
        //                    $remain = $remain < 0 ? 0 : $remain;
        //                    $remain;
        //                } else if ($type == RULES_AUTO_INVOICE && $ownerPlan->max_auto_invoice != -1) {
        //                    $limit = $ownerPlan->max_auto_invoice;
        //                    $used = InvoiceRecurringSetting::where('owner_user_id', $userId)->count();
        //                    $remain = $limit - $used;
        //                    $remain = $remain < 0 ? 0 : $remain;
        //                    $remain;
        //                } else if ($type == RULES_MAINTAINER && $ownerPlan->max_maintainer != -1) {
        //                    $limit = $ownerPlan->max_maintainer;
        //                    $used = Maintainer::where('owner_user_id', $userId)->count();
        //                    $remain = $limit - $used;
        //                    $remain = $remain < 0 ? 0 : $remain;
        //                    $remain;
        //                } else {
        //                    $remain = true;
        //                }
        //                if ($ownerPlan->package_type == PACKAGE_TYPE_PROPERTY) {
        //                    if ($type != RULES_PROPERTY) {
        //                        return $remain;
        //                    }
        //                    $used = Property::where('owner_user_id', $userId)->count();
        //                    $remain = $quantity - $used;
        //                    $remain = $remain < 0 ? 0 : $remain;
        //                    return $remain;
        //                } else if ($ownerPlan->package_type == PACKAGE_TYPE_UNIT) {
        //                    if ($type != RULES_UNIT) {
        //                        return $remain;
        //                    }
        //                    $propertiesIds = Property::query()
        //                        ->where('owner_user_id', $userId)
        //                        ->select('id')
        //                        ->pluck('id')
        //                        ->toArray();
        //                    $used = PropertyUnit::whereIn('property_id', $propertiesIds ?? [])->count();
        //                    $remain = $quantity - $used;
        //                    $remain = $remain < 0 ? 0 : $remain;
        //                    return $remain;
        //                } else if ($ownerPlan->package_type == PACKAGE_TYPE_TENANT) {
        //                    if ($type != RULES_TENANT) {
        //                        return $remain;
        //                    }
        //                    $used = Tenant::where('owner_user_id', $userId)->count();
        //                    $remain = $quantity - $used;
        //                    $remain = $remain < 0 ? 0 : $remain;
        //                    return $remain;
        //                }
        //            }
        //        }

        // old package limit check method
        if ($type == RULES_PROPERTY) {
            if (is_null($ownerPlan)) {
                return 0;
            }
            $limit = $ownerPlan->max_property;
            $used = Property::where('owner_user_id', $userId)->count();
            $remain = $limit - $used;
            $remain = $remain < 0 ? 0 : $remain;
            return $remain;
        } elseif ($type == RULES_MAINTAINER) {
            if (is_null($ownerPlan)) {
                return 0;
            }
            if ($ownerPlan->max_maintainer == -1) {
                return true;
            }
            $limit = $ownerPlan->max_maintainer;
            $used = Maintainer::where('owner_user_id', $userId)->count();
            $remain = $limit - $used;
            $remain = $remain < 0 ? 0 : $remain;
            return $remain;
        } elseif ($type == RULES_TENANT) {
            if (is_null($ownerPlan)) {
                return 0;
            }
            return 1;
            //            $limit = $ownerPlan->max_tenant;
            //            $used = Tenant::where('owner_user_id', $userId)->count();
            //            $remain = $limit - $used;
            //            $remain = $remain < 0 ? 0 : $remain;
            //            return $remain;
        } elseif ($type == RULES_UNIT) {
            if (is_null($ownerPlan)) {
                return 0;
            }
            $limit = $ownerPlan->max_unit;
            $propertyIds = Property::where('owner_user_id', $userId)->select('id')->pluck('id')->toArray();
            $used = PropertyUnit::whereIn('property_id', $propertyIds)->count();
            $remain = $limit - $used;
            $remain = $remain < 0 ? 0 : $remain;
            return $remain;
        } elseif ($type == RULES_INVOICE) {
            if (is_null($ownerPlan)) {
                return 0;
            }
            if ($ownerPlan->max_invoice == -1) {
                return true;
            }
            $limit = $ownerPlan->max_invoice;
            $used = Invoice::where('owner_user_id', $userId)->count();
            $remain = $limit - $used;
            $remain = $remain < 0 ? 0 : $remain;
            return $remain;
        } elseif ($type == RULES_AUTO_INVOICE) {
            if (is_null($ownerPlan)) {
                return 0;
            }
            if ($ownerPlan->max_auto_invoice == -1) {
                return true;
            }
            $limit = $ownerPlan->max_auto_invoice;
            $used = InvoiceRecurringSetting::where('owner_user_id', $userId)->count();
            $remain = $limit - $used;
            $remain = $remain < 0 ? 0 : $remain;
            return $remain;
        }
    }
}

if (!function_exists('getExistingMaintainer')) {
    function getExistingMaintainers($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $ownerPackage = ownerCurrentPackage($userId);

        if (is_null($ownerPackage)) {
            return 0;
        } else {
            $totalCount = User::query()
                ->where('owner_user_id', $userId)
                ->where('role', USER_ROLE_MAINTAINER)
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('getExistingProperty')) {
    function getExistingProperty($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $ownerPackage = ownerCurrentPackage($userId);

        if (is_null($ownerPackage)) {
            return 0;
        } else {
            $totalCount = Property::query()
                ->where('owner_user_id', $userId)
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('getExistingUnit')) {
    function getExistingUnit($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $ownerPackage = ownerCurrentPackage($userId);

        if (is_null($ownerPackage)) {
            return 0;
        } else {
            $propertyIds = Property::query()
                ->where('owner_user_id', $userId)
                ->select('id')
                ->pluck('id')
                ->toArray();

            $totalCount = PropertyUnit::query()
                ->whereIn('property_id', $propertyIds)
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('getExistingTenant')) {
    function getExistingTenant($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $ownerPackage = ownerCurrentPackage($userId);

        if (is_null($ownerPackage)) {
            return 0;
        } else {
            $totalCount = User::query()
                ->where('owner_user_id', $userId)
                ->where('role', USER_ROLE_TENANT)
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('getExistingInvoice')) {
    function getExistingInvoice($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $ownerPackage = ownerCurrentPackage($userId);

        if (is_null($ownerPackage)) {
            return 0;
        } else {
            $totalCount = Invoice::query()
                ->where('owner_user_id', $userId)
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('getExistingAutoInvoice')) {
    function getExistingAutoInvoice($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $ownerPackage = ownerCurrentPackage($userId);

        if (is_null($ownerPackage)) {
            return 0;
        } else {
            $totalCount = InvoiceRecurringSetting::query()
                ->where('owner_user_id', $userId)
                ->count();
            return $totalCount;
        }
    }
}


if (!function_exists('getOwnerUserId')) {
    function getOwnerUserId($user_id = null)
    {
        $user = is_null($user_id) ? auth()->user() : User::find($user_id);
        return $user->owner_user_id ? $user->owner_user_id : $user->id;
    }
}

if (!function_exists('getPackageOtherFields')) {
    function getPackageOtherFields($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $userPackage = ownerCurrentPackage($userId);
        if (is_null($userPackage)) {
            return [];
        } else {
            $package = Package::find($userPackage->package_id);
            return json_decode($package->others);
        }
    }
}


if (!function_exists('syncMissingGateway')) {
    function syncMissingGateway(): void
    {
        $owners = \App\Models\Owner::all();
        $gateways = getPaymentServiceClass(); // Get all the available gateways

        // Loop through each tenant
        foreach ($owners as $owner) {
            // Get all existing gateways for the current tenant
            $existingGateways = \App\Models\Gateway::where('owner_user_id', $owner->user_id)->pluck('slug')->toArray();

            // Loop through each gateway in the payment services list
            foreach ($gateways as $gatewaySlug => $gatewayService) {
                // If the gateway doesn't exist for the tenant, insert it
                if (!in_array($gatewaySlug, $existingGateways)) {
                    // Insert missing gateway for the tenant
                    $gateway = new \App\Models\Gateway();
                    $gateway->owner_user_id = $owner->user_id;
                    $gateway->title = ucfirst($gatewaySlug);
                    $gateway->slug = $gatewaySlug;
                    $gateway->image = 'assets/images/gateway-icon/' . $gatewaySlug . '.png';
                    $gateway->status = 1;
                    $gateway->mode = 2; // Assuming '2' is the default mode
                    $gateway->created_at = now();
                    $gateway->updated_at = now();
                    $gateway->save();

                    // Insert currency for the new gateway
                    $currency = new \App\Models\GatewayCurrency();
                    $currency->owner_user_id = $owner->user_id;
                    $currency->gateway_id = $gateway->id;
                    $currency->currency = 'USD';
                    $currency->conversion_rate = 1.0;
                    $currency->created_at = now();
                    $currency->updated_at = now();
                    $currency->save();
                }
            }
        }

        //super admin
        $admin = \App\Models\User::where('role', USER_ROLE_ADMIN)->first();

        // Get all existing gateways for the current tenant
        $existingGateways = \App\Models\Gateway::where('owner_user_id', $admin->id)->pluck('slug')->toArray();

        // Loop through each gateway in the payment services list
        foreach ($gateways as $gatewaySlug => $gatewayService) {
            // If the gateway doesn't exist for the tenant, insert it
            if (!in_array($gatewaySlug, $existingGateways)) {
                // Insert missing gateway for the tenant
                $gateway = new \App\Models\Gateway();
                $gateway->owner_user_id = $admin->id;
                $gateway->title = ucfirst($gatewaySlug);
                $gateway->slug = $gatewaySlug;
                $gateway->image = 'assets/images/gateway-icon/' . $gatewaySlug . '.png';
                $gateway->status = 1;
                $gateway->mode = 2; // Assuming '2' is the default mode
                $gateway->created_at = now();
                $gateway->updated_at = now();
                $gateway->save();

                // Insert currency for the new gateway
                $currency = new \App\Models\GatewayCurrency();
                $currency->owner_user_id = $admin->id;
                $currency->gateway_id = $gateway->id;
                $currency->currency = 'USD';
                $currency->conversion_rate = 1.0;
                $currency->created_at = now();
                $currency->updated_at = now();
                $currency->save();
            }
        }
    }
}
if (!function_exists('isOnline')) {
    function isOnline($last_seen) {
        return Carbon::parse($last_seen)->gte(now());
    }
}

if (!function_exists('getSubText')) {
    function getSubText($html, $limit= 100000)
    {
        return \Illuminate\Support\Str::limit(strip_tags($html), $limit);
    }
}