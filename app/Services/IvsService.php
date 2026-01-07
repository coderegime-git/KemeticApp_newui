<?php

namespace App\Services;

use Aws\Ivs\IvsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

class IvsService
{
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = config('ivs');
        
        // $this->client = new IvsClient([
        //     'credentials' => $this->config['credentials'],
        //     'region' => $this->config['region'],
        //     'version' => $this->config['version']
        // ]);

          $clientConfig = [
            'credentials' => $this->config['credentials'],
            'region' => $this->config['region'],
            'version' => $this->config['version'],
            'http' => [
                'verify' => false  // THIS DISABLES SSL VERIFICATION
            ]
        ];

        $this->client = new IvsClient($clientConfig);
    }

    /**
     * Create a new IVS channel
     */
    public function createChannel(string $name, array $options = [])
    {
        try {
            $defaultConfig = $this->config['default_channel_config'];
            $params = array_merge($defaultConfig, [
                'name' => $name,
            ], $options);

            // Remove null values
            $params = array_filter($params, function($value) {
                return !is_null($value);
            });

            $result = $this->client->createChannel($params);

            return [
                'success' => true,
                'data' => $result->toArray(),
                'channel' => $result['channel'],
                'streamKey' => $result['streamKey']
            ];

        } catch (AwsException $e) {
            Log::error('IVS Channel Creation Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getAwsErrorCode()
            ];
        }
    }

    /**
     * Get channel by ARN
     */
    public function getChannel(string $channelArn)
    {
        try {
            $result = $this->client->getChannel([
                'arn' => $channelArn
            ]);

            return [
                'success' => true,
                'channel' => $result->toArray()
            ];

        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * List all channels
     */
    public function listChannels($maxResults = 50)
    {
        try {
            $result = $this->client->listChannels([
                'maxResults' => $maxResults
            ]);

            return [
                'success' => true,
                'channels' => $result->toArray()
            ];

        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create stream key for existing channel
     */
    public function createStreamKey(string $channelArn)
    {
        try {
            $result = $this->client->createStreamKey([
                'channelArn' => $channelArn
            ]);

            return [
                'success' => true,
                'streamKey' => $result->toArray()
            ];

        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a channel
     */
    public function deleteChannel(string $channelArn)
    {
        try {
            $this->client->deleteChannel([
                'arn' => $channelArn
            ]);

            return [
                'success' => true,
                'message' => 'Channel deleted successfully'
            ];

        } catch (AwsException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}