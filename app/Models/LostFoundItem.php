<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'lost_found_item_id';

    protected $fillable = [
        'user_id',
        'instansi_id',
        'type',
        'item_name',
        'description',
        'location',
        'date',
        'contact_info',
        'status_item',
    ];

    /**
     * Item ini dilaporkan oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Item ini ditemukan di satu Instansi.
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
}