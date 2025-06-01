<?php

namespace App\Core\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;
use App\Core\Interfaces\IResourceController;
use App\Core\Interfaces\IResourceService;
use App\Core\Services\RequestValidatorService;

abstract class ResourceController extends BaseController implements IResourceController
{
    use ApiResponse;

    protected IResourceService $service;
    protected RequestValidatorService $validator;
    protected array $validationRules = [];
    protected string $label = 'Resource';

    public function __construct(IResourceService $service, array $validationRules = [], string $label = 'Resource')
    {
        $this->service = $service;
        $this->validationRules = $validationRules;
        $this->label = Str::singular($label);
    }

    public function index(Request $request): JsonResponse
    {
        $data = $this->service->getPaginated($request->all());
        return $this->successResponse($data);
    }

    public function store(Request $request): JsonResponse
    {
        $rules = $this->validationRules['store'] ?? [];
        $validatedData = $this->validator->validate($request, $rules);
        $data = $this->service->create($validatedData);
        return $this->successResponse($data, "{$this->label} created successfully", 201);
    }

    public function show(string $identifier): JsonResponse
    {
        $data = $this->service->find($identifier);
        return $this->successResponse($data);
    }

    public function update(Request $request, string $identifier): JsonResponse
    {
        $rules = $this->validationRules['update'] ?? [];
        $validatedData = $this->validator->validate($request, $rules);
        $data = $this->service->update($identifier, $validatedData);
        return $this->successResponse($data, "{$this->label} updated successfully");
    }

    public function destroy(string $identifier): JsonResponse
    {
        $this->service->delete($identifier);
        return $this->successResponse(null, "{$this->label} deleted successfully", 204);
    }
}
