<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
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


    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    public function studentDetail()
    {
        return $this->hasOne(StudentDetail::class);
    }

    public function parentDetail()
    {
        return $this->hasOne(ParentDetail::class);
    }

    /**
     * Get all students (children) linked to this parent user.
     * Only meaningful for users with role='parent'.
     */
    public function children()
    {
        return $this->hasMany(StudentDetail::class, 'parent_id');
    }
}
