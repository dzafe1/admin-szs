<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class KorisniciController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $korisnici = DB::table('users')->get();

        return view('users', ['korisnici' => $korisnici]);
    }
}
