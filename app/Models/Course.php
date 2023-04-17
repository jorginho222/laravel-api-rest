<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Course extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'id',
        'name',
        'description',
        'max_students',
        'price',
        'area_id',
        'modality',
        'init_date',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     *  Enrollments effectuated by students
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     *  Ratings effectuated by students
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'course_id');
    }

    /**
     *  Instructor that creates a course
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeNotFull(Builder $query)
    {
        return $query->where('is_full', '=', false);
    }

}
