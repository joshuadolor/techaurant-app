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
        $priorityCountries = ['US', 'ES', 'PH'];
        $countries = $countries->sortBy(function($country) use ($priorityCountries) {
            $index = array_search($country->code, $priorityCountries);
            return $index !== false ? $index : PHP_INT_MAX;
        });
        return $this->successResponse($countries->values()->toArray(), 'Countries fetched successfully');
    }
}
