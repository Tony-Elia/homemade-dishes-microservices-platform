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

    public function get($url, $params = [], $headers = [])
    {
        return Http::withHeaders(array_merge([
            $this->role_header => auth()->user()->role,
        ], $headers))->get(env('JAVA_EE_API_URL') . $url, $params)->json();
    }

    public function post($url, $data, $headers = [])
    {
        $role = auth()->check() ? auth()->user()->role : ($data['role'] ?? 'guest');
        return Http::withHeaders(array_merge([
            $this->role_header => $role,
        ], $headers))->post(env('JAVA_EE_API_URL') . $url, $data)->json();
    }

    public function put($url, $data, $headers = [])
    {
        return Http::withHeaders(array_merge([
            $this->role_header => auth()->user()->role,
        ], $headers))->put(env('JAVA_EE_API_URL') . $url, $data)->json();
    }

    public function delete($url, $headers = [])
    {
        return Http::withHeaders(array_merge([
            $this->role_header => auth()->user()->role,
        ], $headers))->delete(env('JAVA_EE_API_URL') . $url)->json();
    }
}

