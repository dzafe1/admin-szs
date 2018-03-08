<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SportistiController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $korisnici = DB::table('fudbaler')->get();

        return view('sportisti', ['korisnici' => $korisnici]);
    }
}