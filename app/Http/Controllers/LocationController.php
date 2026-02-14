<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class LocationController extends Controller
{
    public function provinces(): JsonResponse
    {
        $provinces = Province::query()
            ->selectRaw('code as id, name')
            ->orderBy('name')
            ->get();

        return response()->json($provinces);
    }

    public function cities(string $provinceId): JsonResponse
    {
        $cities = City::query()
            ->selectRaw('code as id, name')
            ->where('province_code', $provinceId)
            ->orderBy('name')
            ->get();

        return response()->json($cities);
    }

    public function districts(string $cityId): JsonResponse
    {
        $districts = District::query()
            ->selectRaw('code as id, name')
            ->where('city_code', $cityId)
            ->orderBy('name')
            ->get();

        return response()->json($districts);
    }

    public function villages(string $districtId): JsonResponse
    {
        $villages = Village::query()
            ->selectRaw('code as id, name')
            ->where('district_code', $districtId)
            ->orderBy('name')
            ->get();

        return response()->json($villages);
    }
}
