<?php

namespace App\Http\Controllers;

use App\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecialProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
        if (Auth::check()) {
            // 認証済みのユーザー
            $user_id = Auth::id();
            $url = "https://admane.jp/ad/p/r?_article=2168&_image=10272&_site=2640&_link=9611&suid={$user_id}";
        } else {
            // 認証されていないユーザー
            $url = "/entries/regist";
        }
    
        return view(
            'special_programs.index',
            ['url' => $url]
        );
    }
}
