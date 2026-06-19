<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\TarifAir;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('tarifAir')->latest()->get();
        return view('pelanggans.index', compact('pelanggans'));
    }

    public function create()
    {
        $tarifs = TarifAir::all();
        // Generate no_pelanggan otomatis: P-YYYYMM-XXXX
        $lastPelanggan = Pelanggan::latest('id')->first();
        $nextId = $lastPelanggan ? $lastPelanggan->id + 1 : 1;
        $autoNo = 'P-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('pelanggans.create', compact('tarifs', 'autoNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pelanggan' => 'required|unique:pelanggans',
            'nama' => 'required',
            'alamat' => 'required',
            'tarif_air_id' => 'required|exists:tarif_airs,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Pelanggan::create($request->all());
        return redirect()->route('pelanggans.index')->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        $tarifs = TarifAir::all();
        return view('pelanggans.edit', compact('pelanggan', 'tarifs'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'no_pelanggan' => 'required|unique:pelanggans,no_pelanggan,' . $pelanggan->id,
            'nama' => 'required',
            'alamat' => 'required',
            'tarif_air_id' => 'required|exists:tarif_airs,id',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $pelanggan->update($request->all());
        return redirect()->route('pelanggans.index')->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggans.index')->with('success', 'Data Pelanggan berhasil dihapus.');
    }
}
