<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_PLANEACION = 'planeacion';
    const ROLE_LINEA = 'linea';
    const ROLE_ESMALTE = 'esmalte';
    const ROLE_ALMACEN = 'almacen';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'password_changed_at', // Nuevo campo para registrar la última vez que se cambió la contraseña
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isFirstLogin()
    {
        return is_null($this->password_changed_at);
    }
}