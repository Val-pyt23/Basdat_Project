<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'report_id',
        'asset_id',
        'assigned_to',
        'created_by',
        'task_description',
        'status',
        'scheduled_date',
        'completed_date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}