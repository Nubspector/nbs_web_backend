<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ConsultationService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class ConsultationController extends Controller 
{
    protected $consultationService;

    public function __construct(ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService;
    }
    /*
    Display a listing of the resource with pagination and optional search.
    */
    public function index(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 10);
            $search = $request->input('search');

            $consultations = $this->consultationService->getAllConsultations($page, $limit, $search);
            return response()->json($consultations, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch consultations.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /* Store a newly created resource in storage. */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'phone_number' => 'required|string|max:15',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('employee', 'email'),
                    Rule::unique('consultation', 'email'), // Validasi unik di tabel consultation
                ],
                'username' => [
                    'required',
                    'string',
                    Rule::unique('employee', 'username'),
                    Rule::unique('consultation', 'username'), // Validasi unik di tabel consultation
                ],
                'password' => 'required|string|min:8',
                'created' => 'required|date',
            ]);

            $consultation = $this->consultationService->createConsultation($validatedData);
            return response()->json($consultation, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create consultation.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /* Display the specified resource. */
    public function show($id)
    {
        try {
            $consultation = $this->consultationService->getConsultationById($id);
            return response()->json($consultation, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Consultation not found.',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch consultation.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /* Update the specified resource in storage. */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'phone_number' => 'required|string|max:15',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('employee', 'email')->ignore($id),
                    Rule::unique('consultation', 'email')->ignore($id), // Validasi unik di tabel consultation
                ],
                'username' => [
                    'required',
                    'string',
                    Rule::unique('employee', 'username')->ignore($id),
                    Rule::unique('consultation', 'username')->ignore($id), // Validasi unik di tabel consultation
                ],
                'password' => 'required|string|min:8',
                'created' => 'required|date',
            ]);

            $consultation = $this->consultationService->updateConsultation($id, $validatedData);
            return response()->json($consultation, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update consultation.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /* Remove the specified resource from storage. */
    public function destroy($id)
    {
        try {
            $consultation = $this->consultationService->deleteConsultation($id);
            return response()->json($consultation, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Consultation not found.',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete consultation.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}