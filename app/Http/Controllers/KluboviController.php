<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class KluboviController extends Controller
{
    public function index(){
        $korisnici = DB::table('clubs')->get();

        return view('klubovi', ['klubovi' => $korisnici]);
    }
}