<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusUpdate extends Model
{
    use HasFactory;

    protected $primaryKey = 'update_id';

    protected $fillable = [
        'report_id',
        'updated_by',
        'notes',
    ];

    public function report()
    {
        return $this->belongsTo(FacilityReport::class, 'report_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}