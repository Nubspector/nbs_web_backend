<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TreatmentService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TreatmentController extends Controller
{
    protected $treatmentService;

    public function __construct(TreatmentService $treatmentService)
    {
        $this->treatmentService = $treatmentService;
    }

    /**
     * Display a listing of the resource with pagination and optional search.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $search = $request->input('search');

            $treatments = $this->treatmentService->getAllTreatments($page, $limit, $search);
            return response()->json($treatments, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch treatments.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|string|max:255',
                'type' => 'required|in:Medical,Non-Medical',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'point_discount' => 'required|integer',
                'created_by' => 'required|integer',  // ID user yang membuat treatment
            ]);

            $treatment = $this->treatmentService->createTreatment($validatedData);
            return response()->json($treatment, Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create treatment.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource by ID.
     */
    public function show(string $id)
    {
        try {
            $treatment = $this->treatmentService->getTreatmentById($id);
            return response()->json($treatment, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Treatment not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch treatment.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|string|max:255',
                'type' => 'required|in:Medical,Non-Medical',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'point_discount' => 'required|integer',
                'created_by' => 'required|integer',  // ID user yang membuat treatment
            ]);

            $treatment = $this->treatmentService->updateTreatment($id, $validatedData);
            return response()->json($treatment, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Treatment not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update treatment.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->treatmentService->deleteTreatment($id);
            return response()->json([
                'message' => 'Treatment deleted successfully.'
            ], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Treatment not found.'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete treatment.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
