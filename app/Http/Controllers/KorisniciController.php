<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KorisniciController extends Controller
{
    public function index(){
        $korisnici = User::where('izbrisano', 0)->get();

        return view('users', ['korisnici' => $korisnici]);
    }

    public function getUserById($id) {
        $user = User::where('id', $id)->where('izbrisano', 0)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function editUser($id, Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => [
                'required',
                Rule::unique('users')->ignore($id)
            ],
            'spol' => [
                'required',
                Rule::in(['Muško', 'Žensko'])
            ],
            'address' => 'nullable|max:255',
            'phone' => 'nullable|max:50|numeric'
        ]);

        if ($validatedData->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validatedData->errors()->all()
            ]);
        }

        // Pripremi array za insert
        array_filter($request->all());

        $user = User::where('id', $id)->where('izbrisano', 0)->first();


        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Korisnik nije pronađen.'
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->spol = $request->spol;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->isAdmin = (int)$request->isAdmin;

        if($user->save()) {

            return response()->json([
                'success' => true,
                'message' => 'Korisnik je uspješno izmjenjen.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Greška pri izmejni korisnika.'
        ]);
    }

    public function deleteUser(Request $request) {
        $user = User::find($request->id);

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Korisnik ne postoji.'
            ]);
        }

        $user->izbrisano = 1;

        if(!$user->save()) {
            return response()->json([
                'success' => false,
                'message' => 'Problem tokom brisanja korisnika.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Korisnik obrisan uspješno.'
        ]);
    }
}
