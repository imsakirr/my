<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class MainController extends Controller
{
    
    public function Home(Request $request){
        return view('dashboard');
    }
}
