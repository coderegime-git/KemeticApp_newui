<?php

namespace App\Services;

use Aws\Sdk;
use Illuminate\Support\Facades\Log;

class IvsService
{
    protected $client;
    protected $config;

    public function __construct()
    {
        $this->config = config('ivs');
        
        // Create AWS SDK instance
        $sdk = new Sdk([
            'version' => 'latest',
            'region' => $this->config['region'],
            'credentials' => $this->config['credentials'],
            'http' => [
                'verify' => false // For local development
            ]
        ]);
        
        // Try to create IVS client
        try {
            $this->client = $sdk->createIvs();
            Log::info('IVS client created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create IVS client: ' . $e->getMessage());
            
            // Fallback: Try IVSRealTime
            try {
                $this->client = $sdk->createIvsRealTime();
                Log::info('IVSRealTime client created as fallback');
            } catch (\Exception $e2) {
                Log::error('Both IVS and IVSRealTime failed: ' . $e2->getMessage());
                throw new \Exception('AWS IVS service not available. Check AWS SDK installation.');
            }
        }
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

            Log::info('Creating IVS channel with params:', $params);

            $result = $this->client->createChannel($params);

            Log::info('IVS channel created successfully');

            return [
                'success' => true,
                'data' => $result->toArray(),
                'channel' => $result['channel'],
                'streamKey' => $result['streamKey']
            ];

        } catch (\Exception $e) {
            Log::error('IVS Channel Creation Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => method_exists($e, 'getAwsErrorCode') ? $e->getAwsErrorCode() : 'GENERAL_ERROR'
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

        } catch (\Exception $e) {
            Log::error('IVS Get Channel Failed: ' . $e->getMessage());
            
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

        } catch (\Exception $e) {
            Log::error('IVS List Channels Failed: ' . $e->getMessage());
            
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

        } catch (\Exception $e) {
            Log::error('IVS Create Stream Key Failed: ' . $e->getMessage());
            
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

        } catch (\Exception $e) {
            Log::error('IVS Delete Channel Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}