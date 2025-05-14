<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\JavaEeApiService;

class AdminSellerRepController extends Controller
{
    protected $api;

    public function __construct(JavaEeApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $reps = $this->api->get('/users/seller-representatives');
        return view('admin.seller_reps', compact('reps'));
    }
}