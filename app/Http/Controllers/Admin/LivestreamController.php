<?php
namespace App\Http\Controllers\Admin;

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
        //$this->authorize('admin_livestream_list');

        $query = Livestream::query()->where('creator_id', auth()->id());

        $channels = $this->filters($query, $request)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => 'Live Stream Channels',
            'channels' => $channels,
        ];

        return view('admin.livestream.lists', $data);
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
        //$this->authorize('admin_livestream_create');

        $channelCount = Livestream::where('creator_id', auth()->id())->count();
        
        if ($channelCount >= 1) {
            return redirect('/panel/livestream')
                ->with('error', 'You can only create one live stream channel. Please delete your existing channel to create a new one.');
        }

        $data = [
            'pageTitle' => 'Create New Live Stream Channel',
        ];

        return view('admin.livestream.create', $data);
    }

    public function store(Request $request)
    {
        //$this->authorize('admin_livestream_create');

        $this->validate($request, [
            'channel_name' => 'required|string|max:255',
            'type' => 'required|in:BASIC,STANDARD,ADVANCED',
            'latency_mode' => 'nullable|in:NORMAL,LOW',
        ]);

        $data = $request->all();
        
        try {
            // DB::beginTransaction();

            // Generate unique channel name
            // $channelName = $data['channel_name'];

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

            
            // dd('hi ');
            // Save to database
            try {
                $ivsChannel = Livestream::create([
                    'channel_name' => $data['channel_name'],
                    'channel_arn' => $channelData['arn'],
                    'ingest_endpoint' => $ingestEndpoint,
                    'stream_key' => $streamKeyData['value'],
                    'stream_key_arn' => $streamKeyData['arn'],
                    'playback_url' => $playbackUrl,
                    'channel_id' => $channelData['id'] ?? Str::random(16),
                    'region' => config('ivs.region'),
                    'type' => $data['type'],
                    'recording_configuration_arn' => null,
                    'tags' => $options['tags'],
                    'is_active' => true,
                    'creator_id' => auth()->id(),
                    'created_at' => time(),
                    'updated_at' => time(),
                ]);
                Log::info('Livestream saved successfully', ['id' => $ivsChannel->id]);
            }
            catch (\Exception $dbError) {
                Log::error('Database save failed:', [
                    'error' => $dbError->getMessage(),
                    'data' => [
                        'channel_name' => $data['channel_name'],
                        'ingest_endpoint' => $ingestEndpoint,
                        'playback_url' => $playbackUrl,
                    ]
                ]);
                // dd($dbError->getMessage());
                
                throw new \Exception('Database save failed: ' . $dbError->getMessage());
            }

            // dd($ivsChannel);
            // dd('hi');
            // DB::commit();

            return redirect(getAdminPanelUrl().'/livestream')->with('success', 'Live stream channel created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to create channel: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        //$this->authorize('admin_livestream_edit');

        $channel = Livestream::findOrFail($id);

        $data = [
            'pageTitle' => 'Edit Live Stream Channel',
            'channel' => $channel
        ];

        return view('admin.livestream.create', $data);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin_livestream_edit');

        $this->validate($request, [
            'channel_name' => 'required|string|max:255',
            'type' => 'required|in:BASIC,STANDARD,ADVANCED',
            'latency_mode' => 'nullable|in:NORMAL,LOW',
            'recording_configuration_arn' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();
        $channel = Livestream::findOrFail($id);

        try {
            DB::beginTransaction();

            // Update channel status in AWS if needed
            // Note: AWS IVS doesn't have a direct update for channel name/type
            // We can only update local database fields
            
            $channel->update([
                'channel_name' => $data['channel_name'],
                'type' => $data['type'],
                'latency_mode' => $data['latency_mode'] ?? 'NORMAL',
                'recording_configuration_arn' => $data['recording_configuration_arn'] ?? null,
                'is_active' => $data['is_active'],
                'updated_at' => time(),
            ]);

            DB::commit();

            return redirect(getAdminPanelUrl().'/livestream')->with('success', 'Live stream channel updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Failed to update channel: ' . $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        //$this->authorize('admin_livestream_delete');

        $channel = Livestream::findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete from AWS IVS

            try {
                $this->ivsService->deleteChannel($channel->channel_arn);
            } catch (\Exception $awsError) {
                dd($awsError->getMessage());
                // Just log the error and continue
                Log::warning('AWS deletion failed (channel might be already deleted): ' . $awsError->getMessage());
            }
            // Delete from database
            $channel->delete();

            DB::commit();

            return redirect(getAdminPanelUrl().'/livestream')->with('success', 'Live stream channel deleted successfully.');

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            
            return back()->with('error', 'Failed to delete channel: ' . $e->getMessage());
        }
    }

    public function createStreamKey($id)
    {
        //$this->authorize('admin_livestream_create_key');

        $channel = Livestream::findOrFail($id);

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
        //$this->authorize('admin_livestream_sync');

        $channel = Livestream::findOrFail($id);

        try {
            $result = $this->ivsService->getChannel($channel->channel_arn);
            
            if (!$result['success']) {
                throw new \Exception('Failed to fetch channel from AWS: ' . ($result['error'] ?? 'Unknown error'));
            }

            $awsChannel = $result['channel'];
            
            // You can update local database with AWS data if needed
            // For example, update playback URL if it changed
            
            return back()->with('success', 'Channel synced successfully with AWS.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync channel: ' . $e->getMessage());
        }
    }

    // AJAX methods for API calls
    public function createChannelAjax(Request $request)
    {
        return $this->createChannel($request);
    }

    public function getChannelAjax($id)
    {
        $channel = Livestream::find($id);
        
        if (!$channel) {
            return response()->json([
                'success' => false,
                'message' => 'Channel not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $channel,
            'streaming_info' => [
                'rtmps_url' => $channel->rtmps_url,
                'rtmp_url' => $channel->rtmp_url,
                'playback_hls_url' => $channel->full_playback_url,
                'stream_key' => $channel->stream_key
            ]
        ]);
    }

    public function deleteChannelAjax($id)
    {
        return $this->deleteChannel($id);
    }

    public function createAdditionalStreamKeyAjax($id)
    {
        $channel = Livestream::find($id);
        
        if (!$channel) {
            return response()->json([
                'success' => false,
                'message' => 'Channel not found'
            ], 404);
        }

        try {
            $result = $this->ivsService->createStreamKey($channel->channel_arn);
            
            if (!$result['success']) {
                throw new \Exception('Failed to create stream key: ' . ($result['error'] ?? 'Unknown error'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Stream key created successfully',
                'stream_key' => $result['streamKey']['value'],
                'stream_key_arn' => $result['streamKey']['arn']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create stream key',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncChannelStatusAjax($id)
    {
        $channel = Livestream::find($id);
        
        if (!$channel) {
            return response()->json([
                'success' => false,
                'message' => 'Channel not found'
            ], 404);
        }

        try {
            $result = $this->ivsService->getChannel($channel->channel_arn);
            
            if (!$result['success']) {
                throw new \Exception('Failed to fetch channel from AWS: ' . ($result['error'] ?? 'Unknown error'));
            }

            $awsChannel = $result['channel'];
            
            return response()->json([
                'success' => true,
                'message' => 'Channel synced successfully',
                'aws_channel_status' => $awsChannel
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync channel',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}