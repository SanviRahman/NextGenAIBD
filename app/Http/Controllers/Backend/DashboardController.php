<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\ClientReview;
use App\Models\Country;
use App\Models\Course;
use App\Models\Membership;
use App\Models\Project;
use App\Models\Service;
use App\Models\Team;
use App\Models\User;
use App\Models\Visitor;
use App\Models\WebsiteViews;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function backend_Home_Index()
    {
        #Get the current date
        $currentDate = Carbon::now()->toDateString();

        $adminCount = Admin::count();
        $blogCount = Blog::count();
        $clientReviewCount = ClientReview::count();
        $countryCount = Country::count();
        $courseCount = Course::count();
        $membershipCount = Membership::count();
        $projectCount = Project::count();
        $serviceCount = Service::count();
        $teamCount = Team::count();
        $userCount = User::count();
        $visitorCount = Visitor::count();
        $websiteViewsCount = WebsiteViews::first()->sum('view');

        // $aboutActiveCount = About::where('status', 1)->count();
        // dd($totalCourseSales);

        return view('backend.dashboard.index', compact(
            'adminCount',
            'blogCount',
            'clientReviewCount',
            'countryCount',
            'courseCount',
            'membershipCount',
            'projectCount',
            'serviceCount',
            'teamCount',
            'userCount',
            'visitorCount',
            'websiteViewsCount',
        ));
    }
}
