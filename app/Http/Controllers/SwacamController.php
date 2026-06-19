<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SwacamController extends Controller
{
    /**
     * Store new swaCam submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'meter_reading' => 'required|integer|min:1',
            'periode' => 'nullable|date_format:Y-m',
            'photo' => 'nullable|image|max:5120',
        ]);

        try {
            $pelanggan = Pelanggan::with('tarifAir')->find($validated['pelanggan_id']);

            // Get previous meter
            $lastBilling = $pelanggan->billings()->latest()->first();
            $meter_awal = $lastBilling?->meter_akhir ?? 0;

            // Calculate pemakaian
            $pemakaian = $validated['meter_reading'] - $meter_awal;

            // Handle photo upload
            $photoPath = null;
            $qualityScore = 0;
            $blurDetected = false;
            $brightnessScore = 50;
            $ocrConfidence = 0;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $photoPath = $file->store('swacam-photos', 'public');

                // Analyze photo quality (client-side already done, but we can add server-side validation)
                // For now, we'll set default values
                $qualityScore = 75;
                $brightnessScore = 65;
                $ocrConfidence = 80;
            }

            // Calculate billing
            $periode = $validated['periode'] ?? now()->format('Y-m');
            $tagihan = [];
            $tagihan_air = 0;
            $biaya_pemeliharaan = 0;
            $total_tagihan = 0;

            if ($pelanggan->tarifAir) {
                $tagihan = $pelanggan->tarifAir->hitungTagihan($pemakaian);
                $tagihan_air = $tagihan['tagihan_air'];
                $biaya_pemeliharaan = $tagihan['biaya_pemeliharaan'];
                $total_tagihan = $tagihan['total_tagihan'];
            }

            // Create billing record (main data) - with photo from OCR
            $billing = Billing::create([
                'no_invoice' => Billing::generateNoInvoice(),
                'pelanggan_id' => $validated['pelanggan_id'],
                'periode' => $periode,
                'meter_awal' => $meter_awal,
                'meter_akhir' => $validated['meter_reading'],
                'pemakaian' => $pemakaian,
                'tagihan_air' => $tagihan_air,
                'abonemen' => $biaya_pemeliharaan,
                'total_tagihan' => $total_tagihan,
                'status_pembayaran' => 'belum_lunas',
                'source' => 'ocr',
                'photo_path' => $photoPath,
                'photo_quality' => $qualityScore
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Submission berhasil disimpan dan data tagihan telah dibuat',
                'billing' => $billing
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get submission history (from billings created by OCR)
     */
    public function history(Request $request)
    {
        try {
            $pelangganId = $request->input('pelanggan_id');

            $query = Billing::with('pelanggan')
                ->where('source', 'ocr')
                ->latest('created_at')
                ->limit(50);

            // Filter by pelanggan if provided
            if ($pelangganId) {
                $query->where('pelanggan_id', $pelangganId);
            }

            $submissions = $query->get()
                ->map(function ($billing) {
                    return [
                        'id' => $billing->id,
                        'no_invoice' => $billing->no_invoice,
                        'pelanggan' => [
                            'id' => $billing->pelanggan->id,
                            'nama' => $billing->pelanggan->nama,
                            'no_pelanggan' => $billing->pelanggan->no_pelanggan,
                        ],
                        'periode' => $billing->periode,
                        'meter_awal' => $billing->meter_awal,
                        'meter_akhir' => $billing->meter_akhir,
                        'pemakaian' => $billing->pemakaian,
                        'tagihan_air' => $billing->tagihan_air,
                        'total_tagihan' => $billing->total_tagihan,
                        'photo_path' => $billing->photo_path ? asset('storage/' . $billing->photo_path) : null,
                        'photo_quality' => $billing->photo_quality,
                        'status' => 'Tersimpan',
                        'created_at' => $billing->created_at,
                    ];
                });

            return response()->json($submissions);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get photo archive (from billings created by OCR with photos)
     */
    public function archive(Request $request)
    {
        try {
            $pelangganId = $request->input('pelanggan_id');

            $query = Billing::with('pelanggan')
                ->where('source', 'ocr')
                ->whereNotNull('photo_path')
                ->latest('created_at')
                ->limit(100);

            // Filter by pelanggan if provided
            if ($pelangganId) {
                $query->where('pelanggan_id', $pelangganId);
            }

            $submissions = $query->get()
                ->map(function ($billing) {
                    return [
                        'id' => $billing->id,
                        'pelanggan' => [
                            'id' => $billing->pelanggan->id,
                            'nama' => $billing->pelanggan->nama,
                            'no_pelanggan' => $billing->pelanggan->no_pelanggan,
                        ],
                        'no_invoice' => $billing->no_invoice,
                        'meter_awal' => $billing->meter_awal,
                        'meter_akhir' => $billing->meter_akhir,
                        'pemakaian' => $billing->pemakaian,
                        'photo_path' => $billing->photo_path ? asset('storage/' . $billing->photo_path) : null,
                        'photo_quality' => $billing->photo_quality,
                        'created_at' => $billing->created_at,
                    ];
                });

            return response()->json($submissions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
