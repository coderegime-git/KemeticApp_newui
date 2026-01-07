<?php
namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
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
    public function sendToToken($token, $title, $body, $data = [])
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        return $this->messaging->send($message);
    }

    /**
     * Send notification to multiple FCM tokens
     */
    public function sendToMultipleTokens(array $tokens, $title, $body, $data = [])
    {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        return $this->messaging->sendMulticast($message, $tokens);
    }
}
