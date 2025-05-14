<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class JavaEeApiService
{
    public function get($url)
    {
        return Http::get(env('JAVA_EE_API_URL') . $url)->json();
    }

    public function post($url, $data)
    {
        return Http::post(env('JAVA_EE_API_URL') . $url, $data)->json();
    }

    public function put($url, $data)
    {
        return Http::put(env('JAVA_EE_API_URL') . $url, $data)->json();
    }

    public function delete($url)
    {
        return Http::delete(env('JAVA_EE_API_URL') . $url)->json();
    }
}

