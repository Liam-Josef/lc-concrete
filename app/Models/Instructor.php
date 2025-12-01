<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function organizations() {
        return $this->belongsToMany(Organization::class);
    }
    public function lessons() {
        return $this->belongsToMany(Lesson::class);
    }
    public function sections() {
        return $this->belongsToMany(Section::class);
    }
    public function courses() {
        return $this->belongsToMany(Course::class);
    }



}
