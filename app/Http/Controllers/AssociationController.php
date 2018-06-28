<?php

namespace App\Http\Controllers;

use App\Association;
use App\Repositories\RegionRepository;
use App\Repositories\SportRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssociationController extends Controller
{
    protected $regionRepository;
    protected $sportRepository;

    public function __construct(RegionRepository $regionRepository, SportRepository $sportRepository) {
        $this->regionRepository = $regionRepository;
        $this->sportRepository = $sportRepository;
    }

    public function getAllAssociations() {
        $associations = Association::with(['sport', 'region'])->get();
        $countries = $this->regionRepository
            ->getCountries();
        $sports = $this->sportRepository
            ->getAll();

        return view('savezi', compact('associations', 'countries', 'sports'));
    }

    public function createAssociation(Request $request) {
        $validator = $validator = Validator::make($request->all(),[
            'image' => 'image|dimensions:min_width=200,min_height=200,max_width=1024,max_height=1024',
            'name' => 'required|max:255|string',
            'established_in' => 'required|date|before:'.date('Y-m-d'),
            'president' => 'required|max:255|string',
            'vice_president' => 'required|max:255|string',
            'description' => 'nullable|max:2000|string',
            'region_id' => 'required|integer|exists:regions,id',
            'sport_id' => 'required|integer|exists:sports,id'
        ]);


        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        } else {
            $image = 'default.png';

            if($request->file('image')){
                $logo = $request->file('image');
                $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
                $destinationPath = config('general.image_paths.association_images');
                $logo->move($destinationPath, $newLogoName);

                $image = $newLogoName;
            }

            $createAssociation = Association::create([
                'image' => $image,
                'name' => $request->get('name'),
                'established_in' => $request->get('established_in'),
                'president' => $request->get('president'),
                'vice_president' => $request->get('vice_president'),
                'description' => $request->get('description'),
                'region_id' => $request->get('region_id'),
                'sport_id' => $request->get('sport_id')
            ]);

            $association = Association::where('id', $createAssociation->id)->with(['region', 'sport'])->first();

            if($createAssociation) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uspješno dodali novi savez.',
                    'association' => $association
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Dodavanje saveza neuspješno.'
            ]);
        }
    }

    public function getAssociationEditForm($id) {

        $association = Association::where('id', $id)
            ->with(['sport', 'region'])
            ->first();

        if(!$association) {
            return null;
        }

        $countries = $this->regionRepository
                ->getCountries();

        $sports = $this->sportRepository
                ->getAll();

        return view('partials.edit-association-form', compact('association', 'countries', 'sports'));
    }

    public function editAssociation($id, Request $request) {
        $validator = $validator = Validator::make($request->all(),[
            'image' => 'image|dimensions:min_width=200,min_height=200,max_width=1024,max_height=1024',
            'name' => 'required|max:255|string',
            'established_in' => 'required|date|before:'.date('Y-m-d'),
            'president' => 'required|max:255|string',
            'vice_president' => 'required|max:255|string',
            'description' => 'nullable|max:2000|string',
            'region_id' => 'required|integer|exists:regions,id',
            'sport_id' => 'required|integer|exists:sports,id'
        ]);


        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        } else {
            $fieldsToUpdate = [
                'name' => $request->get('name'),
                'established_in' => $request->get('established_in'),
                'president' => $request->get('president'),
                'vice_president' => $request->get('vice_president'),
                'description' => $request->get('description'),
                'region_id' => $request->get('region_id'),
                'sport_id' => $request->get('sport_id')
            ];

            if($request->file('image')){
                $logo = $request->file('image');
                $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
                $destinationPath = config('general.image_paths.association_images');
                $logo->move($destinationPath, $newLogoName);

                $fieldsToUpdate['image'] = $newLogoName;
            }

            $updateAssociation = Association::where('id', $id)
                ->update($fieldsToUpdate);

            if($updateAssociation) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uspješno ste izmjenili savez.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Izmjena saveza neuspješna.'
            ]);
        }
    }
}
