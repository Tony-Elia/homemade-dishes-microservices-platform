<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class JavaEeApiService
{
    private $role_header;
    public function __construct()
    {
        $this->role_header = env('USER_ROLE_HEADER', 'X-User-Role');
    }

    public function get($url)
    {
        return Http::withHeaders([
            $this->role_header => auth()->user()->role,
        ])->get(env('JAVA_EE_API_URL') . $url)->json();
    }

    public function post($url, $data)
    {
        return Http::withHeaders([
            $this->role_header => auth()->user()->role,
        ])->post(env('JAVA_EE_API_URL') . $url, $data)->json();
    }

    public function put($url, $data)
    {
        return Http::withHeaders([
            $this->role_header => auth()->user()->role,
        ])->put(env('JAVA_EE_API_URL') . $url, $data)->json();
    }

    public function delete($url)
    {
        return Http::withHeaders([
            $this->role_header => auth()->user()->role,
        ])->delete(env('JAVA_EE_API_URL') . $url)->json();
    }
}

