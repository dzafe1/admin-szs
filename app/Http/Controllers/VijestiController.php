<?php

namespace App\Http\Controllers;

use App\Vijest;
use Illuminate\Http\Request;

class VijestiController extends Controller
{
    public function index() {
        $vijesti = Vijest::with('user', 'kategorija')->get();

        return view('news', compact('vijesti'));
    }

    public function indexWaitingApproval() {
        $vijesti = Vijest::with('user', 'kategorija')->where('odobreno', 0)->get();

        return view('unews', compact('vijesti'));
    }
}
