<?php

namespace App\Http\Controllers;
use App\Hobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        $hobbies  = Hobby::select()
                    ->where('user_id',auth()->id())
                    ->orderby('updated_at','ASC')
                    ->get();

        return view('home')->with([
            "hobbies" => $hobbies,
            'message_success' => Session::get('message_success'),
            'message_warning' => Session::get('message_warning')
        ]);
    }
}
