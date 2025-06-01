<?php

namespace App\Core\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IResourceController
{
    public function index(Request $request): JsonResponse;
    public function store(Request $request): JsonResponse;
    public function show(string $identifier): JsonResponse;
    public function update(Request $request, string $identifier): JsonResponse;
    public function destroy(string $identifier): JsonResponse;
}
