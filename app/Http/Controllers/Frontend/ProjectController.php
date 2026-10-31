<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function frontend_Project_Index()
    {
        $projects = Project::where('status', 1)->latest('id')->paginate(9);;
        return view('frontend.project.index', compact(
            'projects',
        ));
    }

    public function frontend_Project_Single($slug)
    {
        $project = Project::where('slug', $slug)->first();
        $projects = Project::where('status', 1)->latest('id')->paginate(9);
        return view('frontend.project.single', compact(
            'project',
            'projects',
        ));
    }
}
