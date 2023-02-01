<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable, HasRolesAndAbilities;

    protected $with = [
      'areas'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    /**
     *  Enrollements effectuated by user
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    /**
     *  Courses created by an instructor
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    public function areas()
    {
        return $this->hasMany(Area::class, 'user_id');
    }
}
