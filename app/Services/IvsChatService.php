<?php

namespace App\Services;

use Carbon\Carbon;
use Aws\Sdk;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Str;

class IvsChatService
{
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = config('ivs');

        $sdk = new Sdk([
            'version' => 'latest',
            'region' => $this->config['region'],
            'credentials' => $this->config['credentials'],
            'http' => [
                'verify' => false // For local development
            ]
        ]);

        try {
            $this->client = $sdk->createIvsChat();
            Log::info('IVS client created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create IVS client: ' . $e->getMessage());
        }
    }

    public function createRoom(string $livestreamId): array
    {
        $title = $livestreamId;

        $result = $this->client->createRoom([
            'name' => Str::limit($title, 128, ''),
        ]);

        return [
            'arn' => $result['arn'],
            'title' => $title,
        ];
    }


    public function createChatToken(
        string $roomArn,
        string $userId
    ): array {
        $capabilities = [
            'SEND_MESSAGE',
            'DELETE_MESSAGE',
            'DISCONNECT_USER',
        ];

        $durationMinutes = 180; // ✅ MAX ALLOWED

        $result = $this->client->createChatToken([
            'roomIdentifier' => $roomArn,
            'userId' => $userId,
            'capabilities' => $capabilities,
            'sessionDurationInMinutes' => $durationMinutes,
            'attributes' => [
                'role' => 'participant',
            ],
        ]);

        return [
            'token' => $result['token'],
            'capabilities' => $capabilities,
            'expires_at' => Carbon::now()->addMinutes($durationMinutes),
        ];
    }

    public function deleteRoom(string $roomArn): void
    {
        try {
            $this->client->deleteRoom([
                'identifier' => $roomArn,
            ]);
        } catch (\Exception $e) {
            // Room might already be deleted – safe to ignore
            \Log::warning('IVS Chat room deletion failed: ' . $e->getMessage());
        }
    }
}
