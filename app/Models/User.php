<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'instansi_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Seorang User memiliki satu Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Seorang User berada di satu Instansi.
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    /**
     * Apakah user adalah admin (superadmin, admin_sarpras, atau admin_instansi/alias seperti admin_ftmm)
     */
    public function isAdmin(): bool
    {
        $name = $this->role->name ?? '';
        if (in_array($name, ['superadmin', 'admin_sarpras'])) {
            return true;
        }
        // treat roles starting with 'admin_' (e.g. admin_instansi, admin_ftmm) as instansi-admin
        if (Str::startsWith($name, 'admin_')) {
            return true;
        }
        return false;
    }

    /**
     * Apakah user adalah admin khusus untuk instansi (role seperti 'admin_instansi' atau 'admin_ftmm')
     */
    public function isInstansiAdmin(): bool
    {
        $name = $this->role->name ?? '';
        // exclude admin_sarpras which is a different admin type
        if ($name === 'admin_sarpras') {
            return false;
        }
        return Str::startsWith($name, 'admin_') || $name === 'admin_instansi';
    }

    /**
     * Apakah user adalah global admin (superadmin atau admin_sarpras)
     */
    public function isGlobalAdmin(): bool
    {
        $name = $this->role->name ?? '';
        return in_array($name, ['superadmin', 'admin_sarpras']);
    }

    /**
     * Seorang User bisa membuat banyak Laporan Fasilitas.
     */
    public function facilityReports()
    {
        return $this->hasMany(FacilityReport::class, 'user_id');
    }
}