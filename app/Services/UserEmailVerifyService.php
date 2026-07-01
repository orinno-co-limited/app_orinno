<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UserEmailVerifyService
{
    // public function emailVerified($token)
    // {
    //     try {
    //         $user = User::where('verify_token', $token)->first();
    //         if ($user) {
    //             $user->status = USER_STATUS_ACTIVE;
    //             $user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
    //             $user->save();
    //             return true;
    //         }
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }
    
    public function emailVerified($token)
    {
        try {
            $user = User::where('verify_token', $token)->first();
    
            if (!$user) {
                return false;
            }
    
            $user->status = USER_STATUS_ACTIVE;
            $user->verify_token = null; // 🔐 CRITICAL FIX
            $user->email_verified_at = Carbon::now();
    
            $user->save();
    
            return true;
    
        } catch (Exception $e) {
            return false;
        }
    }

    public function getUserByToken($token)
    {
        return User::where('verify_token', $token)->first();
    }
}
