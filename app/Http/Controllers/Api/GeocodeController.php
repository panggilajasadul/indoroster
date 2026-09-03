<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 3) {
            return response()->json([]);
        }

        $cacheKey = 'geocode_'.md5(strtolower($q));

        $result = Cache::remember($cacheKey, 86400, function () use ($q) {
            // 1. Coba OpenStreetMap Nominatim dengan User-Agent Resmi
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'IndorosterApp/1.0 (info@indoroster.com)',
                    'Accept-Language' => 'id,en;q=0.9',
                ])->timeout(4)->get('https://nominatim.openstreetmap.org/search', [
                    'format' => 'json',
                    'q' => $q.', Indonesia',
                    'limit' => 1,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (! empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                        return [
                            [
                                'lat' => (float) $data[0]['lat'],
                                'lon' => (float) $data[0]['lon'],
                                'display_name' => $data[0]['display_name'] ?? $q,
                            ],
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Fallback
            }

            // 2. Fallback ke Photon Komoot (OSM Geocoder)
            try {
                $photonRes = Http::timeout(4)->get('https://photon.komoot.io/api/', [
                    'q' => $q,
                    'limit' => 1,
                    'lang' => 'default',
                ]);

                if ($photonRes->successful()) {
                    $pData = $photonRes->json();
                    if (! empty($pData['features']) && isset($pData['features'][0]['geometry']['coordinates'])) {
                        $coords = $pData['features'][0]['geometry']['coordinates'];

                        return [
                            [
                                'lat' => (float) $coords[1],
                                'lon' => (float) $coords[0],
                                'display_name' => $pData['features'][0]['properties']['name'] ?? $q,
                            ],
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Gagal kedua provider
            }

            return [];
        });

        return response()->json($result);
    }

    public function reverse(Request $request)
    {
        $lat = (float) $request->input('lat', 0);
        $lon = (float) $request->input('lon', 0);

        if (! $lat || ! $lon) {
            return response()->json(['display_name' => null]);
        }

        $cacheKey = 'reverse_geocode_'.round($lat, 4).'_'.round($lon, 4);

        $result = Cache::remember($cacheKey, 86400, function () use ($lat, $lon) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'IndorosterApp/1.0 (info@indoroster.com)',
                    'Accept-Language' => 'id,en;q=0.9',
                ])->timeout(4)->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'json',
                    'lat' => $lat,
                    'lon' => $lon,
                    'zoom' => 18,
                    'addressdetails' => 1,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (! empty($data['display_name'])) {
                        return [
                            'display_name' => $data['display_name'],
                            'address' => $data['address'] ?? [],
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Fallback
            }

            return ['display_name' => null];
        });

        return response()->json($result);
    }
}
