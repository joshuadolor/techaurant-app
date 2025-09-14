<?php

namespace App\Resources\Menu\Controllers;

use App\Core\Controllers\ResourceController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Core\Services\FileUploadService;
use App\Core\Services\RequestValidatorService;
use App\Core\Interfaces\IResourceService;


class MenuItemController extends ResourceController
{
    public function store(Request $request): JsonResponse
    {
        $rules = $this->getValidationRules('store', $request);
        $validatedData = $this->validator->validate($request, $rules);

        // Handle logo upload
        if ($request->hasFile('primary_image_url')) {
            $fileUploadService = app(FileUploadService::class);
            $logoUrl = $fileUploadService->uploadLogo($request->file('primary_image_url'));
            
            $validatedData['primary_image_url'] = $logoUrl;
        }

        $data = $this->service->create($validatedData);
        return $this->successResponse($data, "{$this->label} created successfully", 201);
    }

    public function update(Request $request, string $identifier): JsonResponse
    {
        $rules = $this->getValidationRules('update', $request);
        $validatedData = $this->validator->validate($request, $rules);
            // Handle logo upload
        if ($request->hasFile('primary_image_url')) {
            $fileUploadService = app(FileUploadService::class);
            $logoUrl = $fileUploadService->uploadLogo($request->file('primary_image_url'));
            $validatedData['primary_image_url'] = $logoUrl;
        }

        $data = $this->service->update($identifier, $validatedData);
        return $this->successResponse($data, "{$this->label} updated successfully");
    }

    public function getValidationRules(string $type, Request $request): array
    {
        $rules = $this->validationRules[$type] ?? [];
        if ($type === 'update') {
            $rules = array_merge($rules, [
                'name' => 'sometimes|string|max:255|unique:menu_items,name,' . $request->route('menu_item') . ',uuid,owner_id,' . $request->owner_id,
            ]);
        } else {
            $rules = array_merge($rules, [
                'name' => 'required|string|max:255|unique:menu_items,name,NULL,id,owner_id,' . $request->owner_id,
            ]); 
        }
        return $rules;
    }

}