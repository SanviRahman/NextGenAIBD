<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{
    public function frontend_Success_Story_Index()
    {
        $successStory = SuccessStory::first();
        return view('frontend.success-story.index', compact(
            'successStory',
        ));
    }
}
