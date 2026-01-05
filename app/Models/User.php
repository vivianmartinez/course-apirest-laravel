<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method \Illuminate\Support\Collection getRoleNames()
 * @method bool hasRole(string $role)
 * @method \Spatie\Permission\Models\Role[] roles()
 * @method void assignRole(...$roles)
 * @method void removeRole(...$roles)
 * @method \Illuminate\Support\Collection getAllPermissions()
 */

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function tasks() { 
        return $this->hasMany(Task::class,'created_by'); 
    } 
    
    public function comments() { 
        return $this->hasMany(Comment::class); 
    }

    // Implementar la interfaz JWT
    public function getJWTIdentifier() { 
        return $this->getKey(); 
    } 

    public function getJWTCustomClaims() { 
        return []; 
    }
}
