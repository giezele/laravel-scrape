<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/redis-test', function () {
    try {
        $redis = app('redis');
        $redis->set('test_key', 'test_value');
        $value = $redis->get('test_key');
        return response()->json(['status' => 'success', 'value' => $value]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
});

