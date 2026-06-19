<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'no_invoice',
        'pelanggan_id',
        'periode',
        'meter_awal',
        'meter_akhir',
        'pemakaian',
        'tagihan_air',
        'abonemen',
        'diskon',
        'total_tagihan',
        'status_pembayaran',
        'tanggal_bayar'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->no_invoice)) {
                $model->no_invoice = static::generateNoInvoice();
            }
        });
    }

    public static function generateNoInvoice()
    {
        $tahun = date('Y');
        $bulan = date('m');
        $lastInvoice = static::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('id', 'desc')
            ->first();

        $nomor = $lastInvoice ? (int)substr($lastInvoice->no_invoice, -3) + 1 : 1;
        return 'INV-' . $tahun . '/' . $bulan . '/' . str_pad($nomor, 3, '0', STR_PAD_LEFT);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Hitung tagihan berdasarkan tarif progresif pelanggan
     */
    public function hitungTagihanProgresif()
    {
        if (!$this->pelanggan || !$this->pelanggan->tarifAir) {
            throw new \Exception('Pelanggan atau Tarif Air tidak ditemukan');
        }

        $tarif = $this->pelanggan->tarifAir;
        $hasil = $tarif->hitungTagihan($this->pemakaian);

        $this->tagihan_air = $hasil['tagihan_air'];
        $this->abonemen = $hasil['biaya_pemeliharaan']; // Disimpan di kolom abonemen
        $this->total_tagihan = $hasil['total_tagihan'];

        return $hasil;
    }
}
