<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Validation\ValidationException;

abstract class ResourceController extends Controller
{
    use ApiResponse;

    protected $model;
    protected $validationRules = [];
    protected $updateValidationRules = [];
    protected $searchableFields = [];
    protected $relationships = [];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->model::query();

        // Handle search
        if ($request->has('search') && !empty($this->searchableFields)) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                foreach ($this->searchableFields as $field) {
                    $q->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        // Handle relationships
        if (!empty($this->relationships)) {
            $query->with($this->relationships);
        }

        // Handle sorting
        if ($request->has('sort_by')) {
            $direction = $request->get('sort_direction', 'asc');
            $query->orderBy($request->get('sort_by'), $direction);
        }

        // Handle pagination
        $perPage = $request->get('per_page', 15);
        $data = $query->paginate($perPage);

        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $this->model::create($request->all());

        return $this->successResponse($data, 'Resource created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $query = $this->model::query();
        
        if (!empty($this->relationships)) {
            $query->with($this->relationships);
        }

        $data = $query->findOrFail($id);

        return $this->successResponse($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Use update validation rules if defined, otherwise use store rules
        $rules = !empty($this->updateValidationRules) 
            ? $this->updateValidationRules 
            : $this->validationRules;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $data = $this->model::findOrFail($id);
        $data->update($request->all());

        return $this->successResponse($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $data = $this->model::findOrFail($id);
        $data->delete();

        return $this->successResponse(null, 'Resource deleted successfully');
    }
}