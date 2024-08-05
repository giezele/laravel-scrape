<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobCreationRequest;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;

/**
 * Class JobController
 *
 * @package App\Http\Controllers
 */
class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {
    }

    /**
     * @param JobCreationRequest $request
     * @return JsonResponse
     */
    public function create(JobCreationRequest $request): JsonResponse
    {
        $jobIds = $this->jobService->createJobs($request->validated());

        return response()->json(['ids' => $jobIds], 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $jobData = $this->jobService->getJobData($id);
        if (!$jobData) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        return response()->json($jobData);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $this->jobService->deleteJob($id);

        return response()->json(null, 204);
    }
}
