<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityReport extends Model
{
    use HasFactory;

    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'category_id',
        'asset_id',
        'instansi_id',
        'assigned_to',
        'title',
        'description',
        'location',
        'status',
        'attachment_path',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class, 'facility_report_id', 'report_id');
    }
    
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    /**
     * TAMBAHKAN FUNGSI INI
     * Sebuah laporan bisa memiliki banyak rating.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'report_id', 'report_id');
    }
}