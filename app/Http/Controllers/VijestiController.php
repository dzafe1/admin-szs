<?php

namespace App\Http\Controllers;

use App\Vijest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VijestiController extends Controller
{
    public function index() {
        $vijesti = Vijest::with('user', 'kategorija')->where('odobreno', 1)->where('izbrisano', 0)->get();

        return view('news', compact('vijesti'));
    }

    public function indexWaitingApproval() {
        $vijesti = Vijest::with('user', 'kategorija')->where('odobreno', 0)->where('izbrisano', 0)->get();

        return view('unews', compact('vijesti'));
    }

    public function approveNews(Request $request) {
        $vijest = Vijest::find($request->id);

        if(!$vijest) {
            return response()->json([
                'success' => false,
                'message' => 'Vijest ne postoji.'
            ]);
        }

        $vijest->odobreno = 1;

        if(!$vijest->save()) {
            return response()->json([
                'success' => false,
                'message' => 'Problem tokom odobravanja vijesti.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vijest odobrena uspješno.'
        ]);
    }

    public function getNewsById($id) {
        $vijest = Vijest::with('tagovi')->where('id', $id)->where('izbrisano', 0)->first();

        if(!$vijest) {
            return response()->json([
                'success' => false,
            ]);
        }

        return response()->json([
           'success' => true,
           'vijest' => $vijest
        ]);
    }

    public function deleteNews(Request $request) {
        $vijest = Vijest::find($request->id);

        if(!$vijest) {
            return response()->json([
                'success' => false,
                'message' => 'Vijest ne postoji.'
            ]);
        }

        $vijest->izbrisano = 1;

        if(!$vijest->save()) {
            return response()->json([
                'success' => false,
                'message' => 'Problem tokom odbijanja vijesti.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vijest odbijena uspješno.'
        ]);
    }
}
