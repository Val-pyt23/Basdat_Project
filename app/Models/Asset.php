<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $primaryKey = 'asset_id';

    protected $fillable = [
        'asset_code',
        'name',
        'location',
        'instansi_id',
    ];

    /**
     * Sebuah Asset dimiliki oleh satu Instansi.
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    /**
     * Sebuah Asset bisa memiliki banyak Laporan Fasilitas.
     */
    public function facilityReports()
    {
        return $this->hasMany(FacilityReport::class, 'asset_id');
    }
}