<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $fillable = ['no_pelanggan', 'nama', 'perumahan', 'blok', 'no_telp', 'tarif_air_id', 'status', 'meter_terakhir'];

    public function tarifAir()
    {
        return $this->belongsTo(TarifAir::class);
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function swacamSubmissions()
    {
        return $this->hasMany(SwacamSubmission::class);
    }
}
