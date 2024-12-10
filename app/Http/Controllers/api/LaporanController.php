<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LaporanService;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    protected $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }
    //customer baru per bulan
    public function customerBaruPerBulan(Request $request)
    {
        try {
            $validatedData = $request->validate ([
                'tahun' => 'required|integer|min:1',
            ]);


            $laporan = $this->laporanService->laporanCustomerBaruPerBulan($validatedData);
            return response()->json($laporan, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch new customer reports.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //pendapatan per bulan
    public function pendapatanPerBulan(Request $request)
    {
        try {
            $validatedData = $request->validate ([
                'tahun' => 'required|integer|min:1',
            ]);

            $laporan = $this->laporanService->laporanPendapatanPerBulan($validatedData);
            return response()->json($laporan, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch income reports.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //produk terlaris per bulan
    public function produkTerlarisPerBulan(Request $request)
    {
        try {
            $validatedData = $request->validate ([
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:1',
            ]);

            $laporan = $this->laporanService->laporanProdukTerlarisPerBulan($validatedData);
            return response()->json($laporan, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch product reports.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //perawatan terlaris per bulan
    public function perawatanTerlarisPerBulan(Request $request)
    {
        try {
            $validatedData = $request->validate ([
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:1',
            ]);

            $laporan = $this->laporanService->laporanPerawatanTerlarisPerBulan($validatedData);
            return response()->json($laporan, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch product reports.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //jumlah customer yang mengambil perawatan per dokter pada bulan tertentu
    public function customerPerawatanPerDokterPerBulan(Request $request)
    {
        try {
            $validatedData = $request->validate ([
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:1',
            ]);

            $laporan = $this->laporanService->laporanCustomerPerawatanPerDokterPerBulan($validatedData);
            return response()->json($laporan, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch customer treatment reports per doctor.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}