<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifAir extends Model
{
    protected $fillable = [
        'kode',
        'golongan',
        'deskripsi',
        'tarif_blok_1',
        'tarif_blok_2',
        'tarif_blok_3',
        'tarif_blok_4',
        'biaya_pemeliharaan',
        'minimal_pakai_m3'
    ];

    // Konstanta untuk batasan blok (hardcoded)
    const BLOK_1_MAX = 10;
    const BLOK_2_MAX = 20;
    const BLOK_3_MAX = 30;

    /**
     * Hitung tagihan berdasarkan pemakaian dan tarif progresif
     */
    public function hitungTagihan($pemakaian)
    {
        $pemakaian = (int) $pemakaian;
        $minimal = $this->minimal_pakai_m3;

        // Cek minimum pemakaian
        if ($pemakaian < $minimal) {
            $pemakaian = $minimal;
        }

        $tagihanAir = 0;

        // Blok 1: 0 - 10 m³
        if ($pemakaian > 0) {
            $blok1 = min($pemakaian, self::BLOK_1_MAX);
            $tagihanAir += $blok1 * $this->tarif_blok_1;
        }

        // Blok 2: 11 - 20 m³
        if ($pemakaian > self::BLOK_1_MAX) {
            $blok2 = min($pemakaian - self::BLOK_1_MAX, self::BLOK_2_MAX - self::BLOK_1_MAX);
            $tagihanAir += $blok2 * $this->tarif_blok_2;
        }

        // Blok 3: 21 - 30 m³
        if ($pemakaian > self::BLOK_2_MAX) {
            $blok3 = min($pemakaian - self::BLOK_2_MAX, self::BLOK_3_MAX - self::BLOK_2_MAX);
            $tagihanAir += $blok3 * $this->tarif_blok_3;
        }

        // Blok 4: > 30 m³
        if ($pemakaian > self::BLOK_3_MAX) {
            $blok4 = $pemakaian - self::BLOK_3_MAX;
            $tagihanAir += $blok4 * $this->tarif_blok_4;
        }

        $totalTagihan = $tagihanAir + $this->biaya_pemeliharaan;

        return [
            'pemakaian' => $pemakaian,
            'tagihan_air' => $tagihanAir,
            'biaya_pemeliharaan' => $this->biaya_pemeliharaan,
            'total_tagihan' => $totalTagihan
        ];
    }

    public function pelanggans()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
