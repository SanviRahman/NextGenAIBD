<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;

class AdminVisitorCounterController extends Controller
{
    public function backend_Website_Visitor()
    {
        $visitors = Visitor::latest('id')->paginate(100);
        $visitorCount = Visitor::count();

        return view('backend.visitor.index', compact(
            'visitors',
            'visitorCount',
        ));
    }
}
