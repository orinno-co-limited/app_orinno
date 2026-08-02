<?php

namespace App\Services\SmsMail;

use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppCloudService
{
    use ResponseTrait;

    /**
     * WhatsApp only allows free-form text within a 24h window opened by the
     * recipient messaging first. Business-initiated notices (notice board,
     * landlord broadcasts) fall outside that window, so this sends a Meta
     * pre-approved template instead - exempt from the 24h restriction.
     */
    public static function sendWhatsApp($numbers = [], $subject = null, $message = null, $ownerUserId = null)
    {
        $token = getOption('META_WHATSAPP_ACCESS_TOKEN');
        $phoneNumberId = getOption('META_WHATSAPP_PHONE_NUMBER_ID');
        $templateName = getOption('WHATSAPP_TEMPLATE_NAME', 'orinno_notification');
        $languageCode = getOption('WHATSAPP_TEMPLATE_LANGUAGE_CODE', 'en_US');

        if (getOption('WHATSAPP_STATUS', 0) == 1) {
            if (count($numbers)) {
                foreach ($numbers as $key => $number) {
                    $to = preg_replace('/\D/', '', $number);
                    try {
                        $response = Http::withToken($token)
                            ->post("https://graph.facebook.com/v20.0/{$phoneNumberId}/messages", [
                                'messaging_product' => 'whatsapp',
                                'to' => $to,
                                'type' => 'template',
                                'template' => [
                                    'name' => $templateName,
                                    'language' => ['code' => $languageCode],
                                    'components' => [
                                        [
                                            'type' => 'body',
                                            'parameters' => [
                                                ['type' => 'text', 'text' => self::sanitizeParam($subject)],
                                                ['type' => 'text', 'text' => self::sanitizeParam($message)],
                                            ],
                                        ],
                                    ],
                                ],
                            ]);

                        if ($response->successful()) {
                            Log::channel('sms-mail')->info('whatsapp status : sent, number : ' . $number . ', message : ' . $message . 'key : ' . $key . ', date : ' . date('d-m-Y'));
                            TwilioSmsService::historyStore($ownerUserId, $phoneNumberId, self::maskToken($token), $phoneNumberId, $number, $message, SMS_STATUS_DELIVERED, null, 'whatsapp');
                        } else {
                            throw new Exception($response->json('error.message', 'whatsapp send failed'));
                        }
                    } catch (Exception $e) {
                        TwilioSmsService::historyStore($ownerUserId, $phoneNumberId, self::maskToken($token), $phoneNumberId, $number, $message, SMS_STATUS_FAILED, $e->getMessage(), 'whatsapp');
                        Log::channel('sms-mail')->info($e->getMessage());
                    }
                }
                return 'success';
            } else {
                return __('No number found');
            }
        } else {
            return __('Whatsapp setting not enabled');
        }
    }

    /**
     * Template parameters reject newlines/tabs/runs of spaces - collapse whitespace
     * rather than let Meta reject the whole send over formatting.
     */
    private static function sanitizeParam($text)
    {
        return trim(preg_replace('/\s+/', ' ', (string) $text));
    }

    private static function maskToken($token)
    {
        if (!$token) {
            return '';
        }
        return str_repeat('*', max(strlen($token) - 4, 0)) . substr($token, -4);
    }
}
