<?php

namespace App\Resources\Restaurant\Controllers;

use App\Core\Controllers\ResourceController;
use App\Core\Interfaces\IResourceService;
use App\Core\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RestaurantController extends ResourceController
{

    public function store(Request $request): JsonResponse
    {
        $rules = $this->getValidationRules('store', $request);
        $validatedData = $this->validator->validate($request, $rules);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $fileUploadService = app(FileUploadService::class);
            $logoUrl = $fileUploadService->uploadLogo($request->file('logo'));
            
            $request->merge(['logo_url' => $logoUrl]);
        }

        $data = $this->service->create($validatedData);
        return $this->successResponse($data, "{$this->label} created successfully", 201);
    }

    public function update(Request $request, string $identifier): JsonResponse
    {
        $rules = $this->getValidationRules('update', $request);
        $validatedData = $this->validator->validate($request, $rules);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $fileUploadService = app(FileUploadService::class);
            $logoUrl = $fileUploadService->uploadLogo($request->file('logo'));
            
            $request->merge(['logo_url' => $logoUrl]);
        }

        $data = $this->service->update($identifier, $validatedData);
        return $this->successResponse($data, "{$this->label} updated successfully");
    }

    public function getValidationRules(string $type, Request $request): array
    {
        $rules = $this->validationRules[$type] ?? [];
        if ($type === 'update') {
            $rules = array_merge($rules, [
                'name' => 'sometimes|string|max:255|unique:restaurants,name,' . $request->route('restaurant') . ',uuid,owner_id,' . $request->owner_id,
            ]);
        } else {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:255|unique:restaurants,name,NULL,id,owner_id,' . $request->owner_id,
            ]); 
        }
        return $rules;
    }
}