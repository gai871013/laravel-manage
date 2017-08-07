<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    public function getForeverLists(Request $request)
    {
        return [];
    }

    public function getTemporaryLists(Request $request)
    {
        return [1,2,3,4];
    }
}
