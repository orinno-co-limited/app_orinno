<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Services\SmsMail\MailService;
use App\Services\SmsMail\TwilioSmsService;
use App\Services\SmsMail\WhatsAppCloudService;

class NotificationService
{
    /**
     * Fan a single notification out over the requested channels, honoring
     * each recipient's per-channel preference (users.notify_sms/whatsapp/email).
     * Defaults to WhatsApp only - SMS/Email aren't verified yet, so callers
     * must opt in explicitly once those channels are ready.
     */
    public static function send(array $userIds, $subject, $message, $ownerUserId = null, array $channels = ['whatsapp'])
    {
        $users = User::whereIn('id', array_unique(array_filter($userIds)))->get();

        $numbers = [];
        $whatsappNumbers = [];
        $emails = [];

        foreach ($users as $user) {
            if (in_array('sms', $channels) && $user->notify_sms && $user->contact_number) {
                $numbers[] = $user->contact_number;
            }
            if (in_array('whatsapp', $channels) && $user->notify_whatsapp && $user->contact_number) {
                $whatsappNumbers[] = $user->contact_number;
            }
            if (in_array('email', $channels) && $user->notify_email && $user->email) {
                $emails[] = $user->email;
            }
        }

        if ($numbers) {
            TwilioSmsService::sendSms($numbers, $message, $ownerUserId);
        }
        if ($whatsappNumbers) {
            WhatsAppCloudService::sendWhatsApp($whatsappNumbers, $subject, $message, $ownerUserId);
        }
        if ($emails) {
            MailService::sendMail($emails, $subject, $message, $ownerUserId);
        }
    }
}
