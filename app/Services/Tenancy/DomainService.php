<?php

namespace App\Services\Tenancy;

use App\Models\Tenancy;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DomainService
{
    use ResponseTrait;
    public function getAllByOwnerUserId()
    {
        $data =  Tenancy::query()
            ->join('domains', 'tenancies.id', '=', 'domains.tenant_id')
            ->whereJsonContains('data', ['owner_user_id' => getOwnerUserId()])
            ->select('tenancies.id', 'domains.custom', 'domains.domain')
            ->first();

        return $data;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $custom = strtolower(str_replace(' ', '', $request->custom));
            $tenancy = Tenancy::query()
                ->whereJsonContains('data', ['owner_user_id' => getOwnerUserId()])
                ->first();
            $centralDomains = Config::get('tenancy.central_domains')[0];
            $appUrlDomains = implode('.', array_slice(explode('.', parse_url($centralDomains, PHP_URL_HOST)), -2));

            if (is_null($tenancy)) {
                $randomDomainName = $this->generateRandomString();
                $domainExists = Tenancy::where('id', $randomDomainName)->exists();
                if ($domainExists) {
                    throw new Exception(__('Please Try Again!'));
                }
                $tenancy = Tenancy::create(['id' => $randomDomainName, 'owner_user_id' => getOwnerUserId()]);
                $domain = $tenancy->id . '.' . $appUrlDomains;
                $tenancy->domains()->create(['domain' => $domain, 'custom' => $request->custom, 'owner_user_id' => getOwnerUserId()]);
                $message = __(CREATED_SUCCESSFULLY);
            } else {
                $domain = $tenancy->id . '.' . $appUrlDomains;
                $tenancy->domains()->update(['domain' => $domain, 'custom' => $custom, 'owner_user_id' => getOwnerUserId()]);
                $message = __(UPDATED_SUCCESSFULLY);
            }

            DB::commit();
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([],  $message);
        }
    }

    public function info()
    {
        $tenancy = Tenancy::query()
            ->whereJsonContains('data', ['owner_user_id' => getOwnerUserId()])
            ->join('domains', 'tenancies.id', '=', 'domains.tenant_id')
            ->select('domains.custom')
            ->first();
        return $tenancy;
    }

    function generateRandomString($length = 8)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
