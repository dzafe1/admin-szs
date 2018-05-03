<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Vijest;
use App\VijestKategorija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class VijestiController extends Controller
{
    public function index() {
        $vijesti = Vijest::with('user', 'kategorija')->where('odobreno', 1)->where('izbrisano', 0)->get();
        $vijest_kategorija = VijestKategorija::all();

        return view('news', compact('vijesti', 'vijest_kategorija'));
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

    public function editNews($id, Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'naslov' => 'required|max:255',
            'kategorija' => 'required',
            'sadrzaj' => 'required',
            'slika' => 'dimensions:min_width=980,min_height=720|max:5120'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validatedData->errors()->all()
            ]);
        }

        // Dobavi iz baze vijest po idu
        $vijest = Vijest::where('id', $id)->where('odobreno', 1)->where('izbrisano', 0)->first();

        if (!$vijest) {
            return response()->json([
                'success' => false,
                'message' => 'Vijest nije pronađena.'
            ]);
        }

        $photoName = null;

        if ($request->slika) {
            // Ucitaj sliku i spasi u /public/images/vijesti/galerija
            $photoName = auth()->user()->id . '_' . time() . '.' . $request->slika->getClientOriginalExtension();
            $request->slika->move(Config::get('general.image_paths.vijesti'), $photoName);

            // Izrezi i optimizuj sliku za naslovnu
            $image_resize = Image::make(Config::get('general.image_paths.vijesti') . $photoName);
            $image_resize->crop(960, 600);
            $image_resize->save(Config::get('general.image_paths.vijesti') . 'naslovna' . $photoName);
        }

        $vijest->naslov = $request->naslov;
        $vijest->sadrzaj = $request->sadrzaj;
        $vijest->vijest_kategorija_id = $request->kategorija;
        is_null($photoName) ? : $vijest->slika = $photoName;

        if($vijest->save()) {
            // Provjera tagova
            if($request->tagovi) {
                $tagovi = explode(',', $request->tagovi);
                $tagoviIds = [];

                foreach ($tagovi as $tag) {
                    $noviTag = Tag::firstOrCreate([
                        'tag' => trim($tag, " ")
                    ]);

                    if($noviTag) {
                        $tagoviIds[] = $noviTag->id;
                    }
                }

                $vijest->tagovi()->sync($tagoviIds);
            }

            return response()->json([
                'success' => true,
                'message' => 'Vijest je uspješno izmjenjena.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Greška pri izmejni vijesti.'
        ]);
    }
}
