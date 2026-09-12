<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MetaConversionsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetaEventController extends Controller
{
    public function __construct(
        protected MetaConversionsService $metaService
    ) {}

    /**
     * Handle incoming CAPI event dispatch request from browser or backend.
     */
    public function dispatchEvent(Request $request): JsonResponse
    {
        if (empty($request->all()) && ! empty($request->getContent())) {
            $json = json_decode($request->getContent(), true);
            if (is_array($json)) {
                $request->merge($json);
            }
        }

        $validated = $request->validate([
            'event_name' => 'required|string|max:100',
            'event_id' => 'nullable|string|max:100',
            'event_source_url' => 'nullable|url|max:500',
            'custom_data' => 'nullable|array',
            'user_data' => 'nullable|array',
        ]);

        $eventName = $validated['event_name'];
        $eventId = $validated['event_id'] ?? null;
        $eventSourceUrl = $validated['event_source_url'] ?? null;
        $customData = $validated['custom_data'] ?? [];
        $userData = $validated['user_data'] ?? [];

        $result = $this->metaService->sendEvent(
            eventName: $eventName,
            customData: $customData,
            userData: $userData,
            eventId: $eventId,
            eventSourceUrl: $eventSourceUrl
        );

        return response()->json($result);
    }
}
