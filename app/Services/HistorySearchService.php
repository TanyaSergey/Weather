<?php

namespace App\Services;

use App\Cities;
use Illuminate\Support\Facades\Redis;

class HistorySearchService
{
    public function addNewHistory(string $city)
    {
        Redis::hset('city-redis-key', $city, $city);
        $cities = new Cities();
        $cities->city = $city;
        $cities->save();
    }

}