<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function frontend_Blog_Index()
    {
        $blogs = Blog::where('status', 1)->latest('id')->paginate(9);;
        return view('frontend.blog.index', compact(
            'blogs',
        ));
    }

    public function frontend_Blog_Single($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $blogs = Blog::where('status', 1)->latest('id')->paginate(9);
        return view('frontend.blog.single', compact(
            'blog',
            'blogs',
        ));
    }
}
