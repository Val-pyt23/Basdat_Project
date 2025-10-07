<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'created_by',
        'title',
        'content',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}