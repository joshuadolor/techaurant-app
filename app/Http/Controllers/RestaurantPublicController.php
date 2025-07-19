<?php

namespace App\Http\Controllers;

use App\Resources\Restaurant\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantPublicController extends Controller
{

    public function showByUuid($uuid)
    {
        // Find restaurant by UUID - only select slug for efficient redirect
        $restaurant = Restaurant::where('uuid', $uuid)
            ->select('slug')
            ->firstOrFail();
        
        // Redirect to the slug-based URL for SEO and consistent user experience
        return redirect()->route('restaurant.show', ['restaurant' => $restaurant->slug], 301);
    }

    public function show(Restaurant $restaurant)
    {
        // Load necessary relationships for meta tags
        $restaurant->load([
            'contact',
            'businessHours',
            'config'
        ]);

        // Add any additional data processing here
        $restaurant->business_hours_formatted = $this->formatBusinessHours($restaurant->businessHours);
        
        return view('restaurant', compact('restaurant'));
    }

    public function menu(Restaurant $restaurant)
    {
        // Menu page logic here
        return view('restaurant.menu', compact('restaurant'));
    }

    private function formatBusinessHours($businessHours)
    {
        if (!$businessHours || $businessHours->isEmpty()) {
            return 'Hours not available';
        }

        $formatted = [];
        $daysOrder = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', ];
        
        foreach ($daysOrder as $day) {
            $hours = $businessHours->where('day_of_week', $day)->first();
            if ($hours) {
                $formatted[] = ucfirst($day) . ': ' . $hours->open_time . ' - ' . $hours->close_time;
            }
        }
        
        return implode(', ', $formatted);
    }
} 