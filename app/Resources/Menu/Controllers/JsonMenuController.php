<?php

namespace App\Resources\Menu\Controllers;

use App\Http\Controllers\Controller;
use App\Resources\Menu\Models\JsonMenu;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JsonMenuController extends Controller
{
    public function show(int $restaurantId): JsonResponse
    {
        $menu = JsonMenu::firstOrCreate(
            ['restaurant_id' => $restaurantId],
            ['data' => [
                'name' => 'Main Menu',
                'categories' => [],
            ]]
        );

        return response()->json(['data' => $menu->data]);
    }

    public function update(Request $request, int $restaurantId): JsonResponse
    {
        $validated = $request->validate([
            'data' => 'required|array',
        ]);

        $menu = JsonMenu::firstOrCreate(
            ['restaurant_id' => $restaurantId]
        );

        $menu->update(['data' => $validated['data']]);

        return response()->json(['data' => $menu->data]);
    }
}


