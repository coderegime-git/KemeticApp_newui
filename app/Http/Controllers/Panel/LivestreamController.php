<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Livestream;
use App\Services\IvsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LivestreamController extends Controller
{
    protected $ivsService;

    public function __construct(IvsService $ivsService)
    {
        $this->ivsService = $ivsService;
    }

    public function index(Request $request)
    {
        //$this->authorize('panel_livestream_list');

        $query = Livestream::query()->where('creator_id', auth()->id());

        $channels = $this->filters($query, $request)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => 'My Live Stream Channels',
            'channels' => $channels,
        ];

        return view(getTemplate() . '.panel.livestream.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $channel_name = $request->get('channel_name', null);
        $type = $request->get('type', null);
        $is_active = $request->get('is_active', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($channel_name)) {
            $query->where('channel_name', 'like', '%' . $channel_name . '%');
        }

        if (!empty($type) && $type != 'all') {
            $query->where('type', $type);
        }

        if (!empty($is_active) && $is_active != 'all') {
            $query->where('is_active', $is_active == 'active');
        }

        return $query;
    }

    public function create()
    {
        //$this->authorize('panel_livestream_create');
        $channelCount = Livestream::where('creator_id', auth()->id())->count();
        
        if ($channelCount >= 1) {
            return redirect('/panel/livestream')
                ->with('error', 'You can only create one live stream channel. Please delete your existing channel to create a new one.');
        }

        $data = [
            'pageTitle' => 'Create New Live Stream Channel',
        ];

        return view(getTemplate() . '.panel.livestream.create', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('panel_livestream_create');

        $this->validate($request, [
            'channel_name' => 'required|string|max:255',
            'type' => 'required|in:BASIC,STANDARD,ADVANCED',
            'latency_mode' => 'nullable|in:NORMAL,LOW',
        ]);

        $data = $request->all();
        
        try {
            // DB::beginTransaction();

            // Generate unique channel name
            $originalName = $request->channel_name;
            
            // Clean the name: remove spaces and special characters
            $cleanName = preg_replace('/[^a-zA-Z0-9-_]/', '', $originalName);
            
            // If after cleaning it's empty, use a default
            if (empty($cleanName)) {
                $cleanName = 'channel';
            }
            
            // Add random string and ensure it's not too long (max 128 chars for AWS)
            $channelName = $cleanName . '-' . Str::random(8);
            $channelName = substr($channelName, 0, 128); 

            // Prepare options for channel creation
            $options = [
                'type' => $data['type'],
                'latencyMode' => $data['latency_mode'] ?? 'NORMAL',
                'tags' => [
                    'environment' => config('app.env'),
                    'created_by' => 'laravel-system'
                ]
            ];

            if (!empty($data['recording_configuration_arn'])) {
                $options['recordingConfigurationArn'] = $data['recording_configuration_arn'];
            }

            // Create channel in AWS IVS
            $result = $this->ivsService->createChannel($channelName, $options);

            // dd($result);

            if (!$result['success']) {
                throw new \Exception('Failed to create IVS channel: ' . ($result['error'] ?? 'Unknown error'));
            }

            
            $channelData = $result['channel'];
            $streamKeyData = $result['streamKey'];

            // Parse ingest endpoint from channel endpoint
            $ingestEndpoint = '';
            if (isset($channelData['ingestEndpoint'])) {
                $urlParts = parse_url($channelData['ingestEndpoint']);
                $ingestEndpoint = $urlParts['host'] ?? '';
            }
            
            // Parse playback URL
            $playbackUrl = '';
            if (isset($channelData['playbackUrl'])) {
                $urlParts = parse_url($channelData['playbackUrl']);
                $playbackUrl = $urlParts['host'] ?? '';
            }

            // Save to database
            $ivsChannel = Livestream::create([
                'channel_name' => $data['channel_name'],
                'channel_arn' => $channelData['arn'],
                'ingest_endpoint' => $ingestEndpoint,
                'stream_key' => $streamKeyData['value'],
                'stream_key_arn' => $streamKeyData['arn'],
                'playback_url' => $playbackUrl,
                'channel_id' => $channelData['id'] ?? \Illuminate\Support\Str::random(16),
                'region' => config('ivs.region'),
                'type' => $data['type'],
                'latency_mode' => $data['latency_mode'] ?? 'NORMAL',
                'recording_configuration_arn' => null,
                'creator_id' => auth()->id(),
                'tags' => $options['tags'],
                'is_active' => true,
                'creator_id' => auth()->id(),
                'created_at' => time(),
                'updated_at' => time(),
            ]);

            DB::commit();

            return redirect('/panel/livestream')->with('success', 'Live stream channel created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to create channel: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        //$this->authorize('panel_livestream_edit');

        $channel = Livestream::where('id', $id)
            ->where('creator_id', auth()->id())
            ->firstOrFail();

        $data = [
            'pageTitle' => 'Edit Live Stream Channel',
            'channel' => $channel
        ];

        return view(getTemplate() . '.panel.livestream.create', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('panel_livestream_edit');

        $this->validate($request, [
            'channel_name' => 'required|string|max:255',
            'type' => 'required|in:BASIC,STANDARD,ADVANCED',
            'latency_mode' => 'nullable|in:NORMAL,LOW',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();
        
        $channel = Livestream::where('id', $id)
            ->where('creator_id', auth()->id())
            ->firstOrFail();

        try {
            DB::beginTransaction();

            $channel->update([
                'channel_name' => $data['channel_name'],
                'type' => $data['type'],
                'latency_mode' => $data['latency_mode'] ?? 'NORMAL',
                'is_active' => $data['is_active'],
                'updated_at' => time(),
            ]);

            DB::commit();

            return redirect('/panel/livestream')->with('success', 'Live stream channel updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to update channel: ' . $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        //$this->authorize('panel_livestream_delete');

        $channel = Livestream::where('id', $id)
            ->where('creator_id', auth()->id())
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Delete from AWS IVS
            try {
                $this->ivsService->deleteChannel($channel->channel_arn);
            } catch (\Exception $awsError) {
                // Just log the error and continue
                Log::warning('AWS deletion failed (channel might be already deleted): ' . $awsError->getMessage());
            }
            
            // Delete from database
            $channel->delete();

            DB::commit();

            return redirect('/panel/livestream')->with('success', 'Live stream channel deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to delete channel: ' . $e->getMessage());
        }
    }

    public function createStreamKey($id)
    {
        //$this->authorize('panel_livestream_create_key');

        $channel = Livestream::where('id', $id)
            ->where('creator_id', auth()->id())
            ->firstOrFail();

        try {
            $result = $this->ivsService->createStreamKey($channel->channel_arn);
            
            if (!$result['success']) {
                throw new \Exception('Failed to create stream key: ' . ($result['error'] ?? 'Unknown error'));
            }

            // Update with new stream key
            $channel->update([
                'stream_key' => $result['streamKey']['value'],
                'stream_key_arn' => $result['streamKey']['arn'],
                'updated_at' => time(),
            ]);

            return back()->with('success', 'New stream key created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create stream key: ' . $e->getMessage());
        }
    }

    public function sync($id)
    {
        //$this->authorize('panel_livestream_sync');

        $channel = Livestream::where('id', $id)
            ->where('creator_id', auth()->id())
            ->firstOrFail();

        try {
            $result = $this->ivsService->getChannel($channel->channel_arn);
            
            if (!$result['success']) {
                throw new \Exception('Failed to fetch channel from AWS: ' . ($result['error'] ?? 'Unknown error'));
            }

            $awsChannel = $result['channel'];
            
            // Update playback URL if changed
            if (isset($awsChannel['playbackUrl'])) {
                $urlParts = parse_url($awsChannel['playbackUrl']);
                $playbackUrl = $urlParts['host'] ?? '';
                
                if ($playbackUrl && $playbackUrl != $channel->playback_url) {
                    $channel->update([
                        'playback_url' => $playbackUrl,
                        'updated_at' => time(),
                    ]);
                }
            }

            return back()->with('success', 'Channel synced successfully with AWS.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync channel: ' . $e->getMessage());
        }
    }
}