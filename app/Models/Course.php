<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function courseCurricula()
    {
        return $this->hasMany(CourseCurriculum::class, 'app_id', 'id');
    }

    public function courseVideoModules()
    {
        return $this->hasMany(CourseVideoModule::class, 'course_id', 'id');
    }

    public function CourseModules()
    {
        return $this->hasMany(CourseModule::class, 'app_id', 'id');
    }

    public function CourseConveniences()
    {
        return $this->hasMany(CourseConvenience::class, 'app_id', 'id');
    }

    public function CourseWhoFors()
    {
        return $this->hasMany(CourseWhoFor::class, 'app_id', 'id');
    }

    public function studentCart()
    {
        return $this->hasMany(StudentCart::class, 'course_id', 'id');
    }

    public function courseBundles()
    {
        return $this->hasMany(CourseBundle::class, 'course_id', 'id');
    }
}
