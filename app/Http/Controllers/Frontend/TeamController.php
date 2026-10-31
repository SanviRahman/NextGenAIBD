<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function frontend_Team_Index()
    {
        $teams = Team::where('status', 1)->latest('id')->paginate(9);;
        return view('frontend.team.index', compact(
            'teams',
        ));
    }

    public function frontend_Team_Single($slug)
    {
        $team = Team::where('slug', $slug)->first();
        $teams = Team::where('status', 1)->latest('id')->paginate(9);
        return view('frontend.team.single', compact(
            'team',
            'teams',
        ));
    }
}
