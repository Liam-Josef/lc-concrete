<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected  $guarded = [];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin'  => 'boolean',
        'is_active' => 'boolean',
    ];


    public function news() {
        return $this->hasMany(News::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $slug): bool
    {
        // Works whether you use a single 'role' column or a pivot
        if (isset($this->role) && $this->role === $slug) return true;
        // If roles not loaded yet, this will lazy load once
        return $this->roles->contains('slug', $slug);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function getPrimaryRoleLabelAttribute(): string
    {
        if ($this->is_admin) return 'Admin';
        if (method_exists($this, 'hasRole') && $this->hasRole('instructor')) return 'Instructor';
        if (method_exists($this, 'hasRole') && $this->hasRole('student')) return 'Student';
        if (!empty($this->role)) return ucfirst($this->role);
        return $this->roles()->whereIn('slug', ['instructor','student'])->pluck('slug')
            ->map(fn($s) => ucfirst($s))->first() ?? 'Member';
    }



}
