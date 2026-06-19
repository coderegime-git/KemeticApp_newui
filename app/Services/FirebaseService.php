<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        //$credentialsPath = base_path(env('FIREBASE_CREDENTIALS'));
        $credentialsPath = storage_path(env('FIREBASE_CREDENTIALS'));

        if (!file_exists($credentialsPath)) {
            throw new \Exception("Firebase credentials file not found: {$credentialsPath}");
        }

        if (!is_readable($credentialsPath)) {
            throw new \Exception("Firebase credentials file is not readable: {$credentialsPath}");
        }

        $factory = (new Factory)
            ->withServiceAccount($credentialsPath);

        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification to single FCM token
     */
    public function sendToToken($token, $title, $body, $data = [], $options = [])
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data)
            ->withAndroidConfig($this->buildAndroidConfig($options, $title))
            ->withApnsConfig($this->buildApnsConfig($options, $title));

        return $this->messaging->send($message);
    }

    /**
     * Send notification to multiple FCM tokens
     */
    public function sendToMultipleTokens(array $tokens, $title, $body, $data = [], $options = [])
    {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data)
            ->withAndroidConfig($this->buildAndroidConfig($options, $title))
            ->withApnsConfig($this->buildApnsConfig($options, $title));

        return $this->messaging->sendMulticast($message, $tokens);
    }

    // private function buildAndroidConfig(array $options = [], $title = ''): array
    // {
    //     $isSos     = $options['is_sos']     ?? false;
    //     $channelId = $options['channel_id'] ?? ($isSos ? 'basic_channel'   : 'basic_channel');
    //     $sound     = $options['sound']      ?? ($isSos ? 'notification_sound'     : 'notification_sound');

    //     return [
    //         'priority'     => 'high',
    //         'notification' => [
    //             'channel_id'    => $channelId,
    //             'sound'         => $sound,
    //             'default_sound' => false,
    //         ],
    //     ];
    // }

    // private function buildApnsConfig(array $options = [], $title = ''): ApnsConfig
    // {
    //     $isSos = $options['is_sos'] ?? false;
    //     $sound  = $options['sound'] ?? 'notification_sound.aiff';

    //     return ApnsConfig::fromArray([
    //         'headers' => [
    //             'apns-priority' => '10',
    //         ],
    //         'payload' => [
    //             'aps' => [
    //                 'sound'             => $sound,
    //                 'badge'             => 1,
    //                 'content-available' => 1,
    //             ],
    //         ],
    //     ]);
    // }

    private function isNewSale(string $title): bool
    {
        return str_contains(strtolower($title), 'product new sale');
    }

    private function buildAndroidConfig(array $options = [], $title = ''): array
    {
        $isSos     = $options['is_sos']     ?? false;
        $isNewSale = $this->isNewSale($title);

        $channelId = $options['channel_id'] ?? ($isSos ? 'basic_channel'   : 'basic_channel');
        $sound     = $options['sound']      ?? ($isNewSale ? 'notification_sound' : 'seeker_sound');

        return [
            'priority'     => 'high',
            'notification' => [
                'channel_id'    => $channelId,
                'sound'         => $sound,
                'default_sound' => false,
            ],
        ];
    }

    private function buildApnsConfig(array $options = [], $title = ''): ApnsConfig
    {
        $isSos = $options['is_sos'] ?? false;
        $isNewSale = $this->isNewSale($title);
        $sound  = $options['sound'] ?? ($isNewSale ? 'notification_sound.aiff' : 'seeker_sound.aiff');

        return ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'sound'             => $sound,
                    'badge'             => 1,
                    'content-available' => 1,
                ],
            ],
        ]);
    }
}
