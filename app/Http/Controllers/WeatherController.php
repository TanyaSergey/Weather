<?php

namespace App\Http\Controllers;

use App\Cities;
use App\Services\OpenWeatherService;
use App\Services\HistorySearchService;
use App\Http\Requests\SearchWeatherRequest;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather');
    }

    public function searchWeather(SearchWeatherRequest $request, OpenWeatherService $service, HistorySearchService $historySearchService)
    {
        $weather = $request->name
            ? $service->getWeatherByGeoName($request->name)
            : $service->getWeatherByGeoCoordinates($request->latitude, $request->longitude);

        if ($request->name ?? $weather->name ?? false) {
            $historySearchService->addNewHistory($request->name ?? $weather->name);
        }

        return response()->json(['data' => $weather ?? null]);
    }

    public function getLatestHistorySearch()
    {
        $cities = Cities::latest()->limit(10)->get()->pluck('city');
        return response()->json(['data' => $cities ?? null]);

    }
}