<?php

namespace App\Common\Countries\Controllers;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Models\Country;

class CountryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $countries = Country::all();
        return $this->successResponse($countries, 'Countries fetched successfully');
    }
}
