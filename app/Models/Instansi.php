<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    // Menentukan nama tabel karena Laravel akan mengasumsikan 'instansis'
    protected $table = 'instansi'; 
    
    protected $primaryKey = 'instansi_id';

    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Satu Instansi memiliki banyak User.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'instansi_id');
    }

    /**
     * Satu Instansi memiliki banyak Asset.
     */
    public function assets()
    {
        return $this->hasMany(Asset::class, 'instansi_id');
    }
}