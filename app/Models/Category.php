<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Satu Kategori bisa dimiliki oleh banyak Laporan Fasilitas.
     */
    public function facilityReports()
    {
        return $this->hasMany(FacilityReport::class, 'category_id');
    }
}