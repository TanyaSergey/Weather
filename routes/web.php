<?php

Route::get('/', 'WeatherController@index');

Route::post('/search-weather', 'WeatherController@searchWeather');

Route::post('/get-list', 'WeatherController@getLatestHistorySearch');