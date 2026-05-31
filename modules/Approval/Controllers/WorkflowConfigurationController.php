<?php

declare(strict_types=1);

namespace Modules\Approval\Controllers;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Approval\Models\WorkflowConfiguration;

class WorkflowConfigurationController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(WorkflowConfiguration::query()->orderBy('workflow_type')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'workflow_type' => ['required', 'string', 'unique:workflow_configurations,workflow_type'],
            'config' => ['required', 'array'],
            'config.steps' => ['required', 'array', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        $config = WorkflowConfiguration::create([
            'workflow_type' => $data['workflow_type'],
            'config' => $data['config'],
            'is_active' => $data['is_active'] ?? true,
            'created_by' => $request->user()->id,
        ]);

        return ApiResponse::success($config, 'Workflow configuration created', status: 201);
    }

    public function update(Request $request, WorkflowConfiguration $configuration): JsonResponse
    {
        $configuration->update($request->validate([
            'config' => ['sometimes', 'array'],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return ApiResponse::success($configuration, 'Workflow configuration updated');
    }
}
