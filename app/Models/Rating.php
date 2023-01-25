<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
      'value',
      'comment',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
