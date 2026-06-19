<?php

namespace App\Http\Controllers;

use App\Models\TarifAir;
use Illuminate\Http\Request;

class TarifAirController extends Controller
{
    public function index()
    {
        $tarifs = TarifAir::all();
        return view('master.tarif-air.index', compact('tarifs'));
    }

    public function create()
    {
        return view('master.tarif-air.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:tarif_airs',
            'golongan' => 'required',
            'tarif_blok_1' => 'required|numeric',
            'tarif_blok_2' => 'required|numeric',
            'tarif_blok_3' => 'required|numeric',
            'tarif_blok_4' => 'required|numeric',
            'biaya_pemeliharaan' => 'required|numeric',
            'minimal_pakai_m3' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        TarifAir::create($request->all());
        return redirect()->route('master.tarif-air.index')->with('success', 'Tarif Air berhasil ditambahkan.');
    }

    public function edit(TarifAir $tarifAir)
    {
        return view('master.tarif-air.edit', compact('tarifAir'));
    }

    public function update(Request $request, TarifAir $tarifAir)
    {
        $request->validate([
            'kode' => 'required|unique:tarif_airs,kode,' . $tarifAir->id,
            'golongan' => 'required',
            'tarif_blok_1' => 'required|numeric',
            'tarif_blok_2' => 'required|numeric',
            'tarif_blok_3' => 'required|numeric',
            'tarif_blok_4' => 'required|numeric',
            'biaya_pemeliharaan' => 'required|numeric',
            'minimal_pakai_m3' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        $tarifAir->update($request->all());
        return redirect()->route('master.tarif-air.index')->with('success', 'Tarif Air berhasil diperbarui.');
    }

    public function destroy(TarifAir $tarifAir)
    {
        $tarifAir->delete();
        return redirect()->route('master.tarif-air.index')->with('success', 'Tarif Air berhasil dihapus.');
    }
}
