<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $billings = Billing::with('pelanggan.tarifAir')->orderBy('periode', 'desc')->get();
        $pelanggans = Pelanggan::where('status', 'aktif')->get();
        return view('billings.index', compact('billings', 'pelanggans'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::where('status', 'aktif')->get();

        // Data bulan dalam bahasa Indonesia
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('billings.create', compact('pelanggans', 'bulanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'periode_bulan' => 'required|numeric|min:1|max:12',
            'periode_tahun' => 'required|numeric',
            'meter_awal' => 'required|numeric|min:0',
            'meter_akhir' => 'required|numeric|gte:meter_awal',
        ]);

        $pelanggan = Pelanggan::with('tarifAir')->findOrFail($request->pelanggan_id);
        $tarif = $pelanggan->tarifAir;

        // Validasi jika belum ada tarif
        if (!$tarif) {
            return back()->with('error', 'Pelanggan tidak memiliki golongan tarif air.');
        }

        // Kalkulasi Progresif menggunakan hitungTagihan dari TarifAir model
        $pemakaian = $request->meter_akhir - $request->meter_awal;
        if ($pemakaian < 0) {
            $pemakaian = 0;
        }
        $tagihan = $tarif->hitungTagihan($pemakaian);

        // Generate periode dengan format YYYY-MM
        $periode = $request->periode_tahun . '-' . str_pad($request->periode_bulan, 2, '0', STR_PAD_LEFT);

        Billing::create([
            'pelanggan_id' => $pelanggan->id,
            'periode' => $periode,
            'meter_awal' => $request->meter_awal,
            'meter_akhir' => $request->meter_akhir,
            'pemakaian' => $tagihan['pemakaian'],
            'tagihan_air' => $tagihan['tagihan_air'],
            'abonemen' => $tagihan['biaya_pemeliharaan'],
            'total_tagihan' => $tagihan['total_tagihan'],
            'status_pembayaran' => 'belum_lunas'
        ]);

        return redirect()->route('billings.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    public function edit(Billing $billing)
    {
        return view('billings.edit', compact('billing'));
    }

    public function update(Request $request, Billing $billing)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_lunas,lunas,batal',
            'diskon' => 'nullable|numeric|min:0',
        ]);

        $billing->update([
            'status_pembayaran' => $request->status_pembayaran,
            'diskon' => $request->diskon ?? 0
        ]);

        return redirect()->route('billings.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Billing $billing)
    {
        $billing->delete();
        return redirect()->route('billings.index')->with('success', 'Tagihan berhasil dihapus.');
    }

    /**
     * API: Get last billing meter untuk pelanggan tertentu
     * Digunakan untuk auto-fill meter_awal saat membuat billing baru
     */
    public function getLastMeter($pelangganId)
    {
        $lastBilling = Billing::where('pelanggan_id', $pelangganId)
            ->latest('periode')
            ->first();

        if ($lastBilling) {
            return response()->json([
                'meter_awal' => $lastBilling->meter_akhir
            ]);
        }

        // Jika tidak ada billing sebelumnya, return 0
        return response()->json([
            'meter_awal' => 0
        ]);
    }

    /**
     * API: Calculate total billing berdasarkan pemakaian dan tarif
     * Digunakan untuk preview tagihan saat membuat billing baru
     */
    public function calculateBilling(Request $request)
    {
        $pelangganId = $request->input('pelanggan_id');
        $meterAwal = (int)$request->input('meter_awal', 0);
        $meterAkhir = (int)$request->input('meter_akhir', 0);

        if (!$pelangganId) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan harus dipilih'
            ], 400);
        }

        $pelanggan = Pelanggan::with('tarifAir')->findOrFail($pelangganId);
        $tarif = $pelanggan->tarifAir;

        if (!$tarif) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan tidak memiliki golongan tarif air'
            ], 400);
        }

        $pemakaian = $meterAkhir - $meterAwal;
        if ($pemakaian < 0) {
            $pemakaian = 0;
        }

        // Gunakan hitungTagihan method dari TarifAir model
        $tagihan = $tarif->hitungTagihan($pemakaian);

        return response()->json([
            'success' => true,
            'pemakaian' => $tagihan['pemakaian'],
            'tagihan_air' => $tagihan['tagihan_air'],
            'biaya_pemeliharaan' => $tagihan['biaya_pemeliharaan'],
            'total_tagihan' => $tagihan['total_tagihan']
        ]);
    }

    /**
     * View: Halaman swaCam Entry & OCR
     */
    public function swacamView()
    {
        $pelanggans = Pelanggan::where('status', 'aktif')->get();
        return view('billings.swacam', compact('pelanggans'));
    }

    /**
     * API: Generate No Invoice
     * Digunakan untuk auto-generate no_invoice di swaCam form
     */
    public function generateInvoice()
    {
        $noInvoice = Billing::generateNoInvoice();
        return response()->json(['no_invoice' => $noInvoice]);
    }

    /**
     * API: Get last meter
     * Helper method untuk mendapatkan meter terakhir dari billing atau swacam submission
     */
    public function getLastMeterForSwacam($pelangganId)
    {
        // First check dari billing
        $lastBilling = Billing::where('pelanggan_id', $pelangganId)
            ->latest('periode')
            ->first();

        if ($lastBilling) {
            return response()->json([
                'meter_awal' => $lastBilling->meter_akhir
            ]);
        }

        // Jika tidak ada billing, return 0
        return response()->json([
            'meter_awal' => 0
        ]);
    }
}
