<?php

namespace App\Http\Controllers\Countries;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;

class CountryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return CountryResource::collection(Country::get());
    }
}
