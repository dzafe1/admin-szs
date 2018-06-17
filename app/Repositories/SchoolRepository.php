<?php

namespace App\Repositories;

use App\Gallery;
use App\School;
use App\SchoolMember;
use App\Trophy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolRepository {
    protected $model;

    public function __construct(School $model)
    {
        $this->model = $model;
    }

    public function createSchool(Request $data) {
        $newLogoName = 'default.png';

        if($data->file('logo')){
            $logo = $data->file('logo');
            $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
            $destinationPath = public_path('/images/school_logo');
            $logo->move($destinationPath, $newLogoName);
        }

        // Provjeri najmanji level regije
        $region_id = $data->get('country');

        if($data->has('province')) {
            $region_id = $data->get('province');
        }

        if($data->has('region')) {
            $region_id = $data->get('region');
        }

        if($data->has('municipality')) {
            $region_id = $data->get('municipality');
        }

        $createSchool = $this->model->create([
            'logo' => $newLogoName,
            'name' => $data->get('name'),
            'nature' => $data->get('nature'),
            'city' => $data->get('city'),
            'established_in' => $data->get('established_in'),
            'home_field' => $data->get('home_field'),
            'competition' => $data->get('competition'),
            'phone_1' => $data->get('phone_1'),
            'phone_2' => $data->get('phone_2'),
            'fax' => $data->get('fax'),
            'email' => $data->get('email'),
            'website' => $data->get('website'),
            'address' => $data->get('address'),
            'pioniri' => $data->get('pioniri'),
            'kadeti' => $data->get('kadeti'),
            'juniori' => $data->get('juniori'),
            'facebook' => $data->get('facebook'),
            'twitter' => $data->get('twitter'),
            'instagram' => $data->get('instagram'),
            'youtube' => $data->get('youtube'),
            'video' => $data->get('video'),
            'club_category_id' => $data->get('category'),
            'sport_id' => $data->get('sport'),
            'region_id' => $region_id,
            'user_id' => Auth::user()->id,
            'history' => $data->get('history')
        ]);

        if($createSchool){

            if($data->filled('nagrada')){
                foreach($data->get('nagrada') as $key => $nagrada){
                    if($nagrada){
                        Trophy::create([
                            'type' => $data['nagrada'][$key]['vrsta'],
                            'place' => $data['nagrada'][$key]['tip'],
                            'competition_name' => $data['nagrada'][$key]['takmicenje'],
                            'level_of_competition' =>  $data['nagrada'][$key]['nivo'],
                            'season' =>  $data['nagrada'][$key]['sezona'],
                            'school_id' => $createSchool->id
                        ]);
                    }
                }
            }

            if($data->filled('licnost')){
                foreach($data->get('licnost') as $key => $licnost){
                    if($licnost){
                        $logo = array_key_exists('avatar', $data['licnost'][$key]) ? $data['licnost'][$key]['avatar'] : null;

                        if($logo) {
                            $newavatarlicnostiName = time() . '-' . $createSchool->id . '.' . $logo->getClientOriginalExtension();
                            $destPath = public_path('/images/avatar_licnost');
                            $logo->move($destPath, $newavatarlicnostiName);
                        } else {
                            $newavatarlicnostiName = 'default_avatar.png';
                        }

                        SchoolMember::create([
                            'avatar' => $newavatarlicnostiName,
                            'firstname' => $data['licnost'][$key]['ime'],
                            'lastname' => $data['licnost'][$key]['prezime'],
                            'biography' => $data['licnost'][$key]['opis'] ? $data['licnost'][$key]['opis'] : null,
                            'school_id' => $createSchool->id
                        ]);
                    }
                }
            }

            if($data->file('galerija')){
                $galerije = $data->file('galerija');
                foreach($galerije as $key => $slika){
                    $newgalName = $key . '-' .time() . '-' .  $createSchool->id . '.' . $slika->getClientOriginalExtension();
                    $destPath = public_path('/images/galerija_skola');
                    $slika->move($destPath, $newgalName);

                    Gallery::create([
                        'image' => $newgalName,
                        'school_id' => $createSchool->id
                    ]);
                }
            }

            return $createSchool;

        }

        return null;
    }

    public function getById($id) {
        return $this->model
            ->where('id', $id)
            ->with(['category', 'sport', 'region', 'members', 'user', 'trophies', 'images'])
            ->first();
    }

    public function updateGeneral(Request $request, School $school) {
        $newLogoName = $school->logo;

        if($request->file('logo')){
            $logo = $request->file('logo');
            $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
            $destinationPath = public_path('/images/school_logo');
            $logo->move($destinationPath, $newLogoName);
        }

        // Provjeri najmanji level regije
        $region_id = $request->get('country');

        if($request->has('province')) {
            $region_id = $request->get('province');
        }

        if($request->has('region')) {
            $region_id = $request->get('region');
        }

        if($request->has('municipality')) {
            $region_id = $request->get('municipality');
        }

        $schoolUpdate = $school->update([
            'logo' => $newLogoName,
            'name' => $request->get('name'),
            'nature' => $request->get('nature'),
            'city' => $request->get('city'),
            'established_in' => $request->get('established_in'),
            'home_field' => $request->get('home_field'),
            'competition' => $request->get('competition'),
            'phone_1' => $request->get('phone_1'),
            'phone_2' => $request->get('phone_2'),
            'fax' => $request->get('fax'),
            'email' => $request->get('email'),
            'website' => $request->get('website'),
            'address' => $request->get('address'),
            'pioniri' => $request->get('pioniri'),
            'kadeti' => $request->get('kadeti'),
            'juniori' => $request->get('juniori'),
            'facebook' => $request->get('facebook'),
            'twitter' => $request->get('twitter'),
            'instagram' => $request->get('instagram'),
            'youtube' => $request->get('youtube'),
            'video' => $request->get('video'),
            'club_category_id' => $request->get('category'),
            'sport_id' => $request->get('sport'),
            'region_id' => $region_id
        ]);

        if($schoolUpdate) {
            return $schoolUpdate;
        }

        return null;

    }

    public function updateStaff(Request $request, School $school) {
        $oldIds = [];

        if($request->filled('licnost')){
            foreach($request->get('licnost') as $key => $licnost){
                if($licnost){
                    $logo = array_key_exists('avatar', $request['licnost'][$key]) ? $request['licnost'][$key]['avatar'] : null;
                    $newavatarlicnostiName = null;
                    // Ako nema id dodaj licnost
                    if(!array_key_exists('id', $licnost)) {
                        if ($logo) {
                            $newavatarlicnostiName = time() . '-' . $school->id . '.' . $logo->getClientOriginalExtension();
                            $destPath = public_path('/images/avatar_licnost');
                            $logo->move($destPath, $newavatarlicnostiName);
                        } else {
                            $newavatarlicnostiName = 'default_avatar.png';
                        }

                        $new_licnost = SchoolMember::create([
                            'avatar' => $newavatarlicnostiName,
                            'firstname' => $request['licnost'][$key]['ime'],
                            'lastname' => $request['licnost'][$key]['prezime'],
                            'biography' => $request['licnost'][$key]['opis'] ? $request['licnost'][$key]['opis'] : null,
                            'school_id' => $school->id
                        ]);

                        $oldIds[] = $new_licnost->id;
                    } else {
                        $old_licnost = SchoolMember::where('id', $licnost['id'])->where('school_id', $school->id)->first();

                        if($old_licnost) {
                            $oldIds[] = $old_licnost->id;

                            $fieldsToUpdate = [
                                'firstname' => $request['licnost'][$key]['ime'],
                                'lastname' => $request['licnost'][$key]['prezime'],
                                'biography' => $request['licnost'][$key]['opis'] ? $request['licnost'][$key]['opis'] : null,
                            ];

                            if ($logo) {
                                $newavatarlicnostiName = time() . '-' . $school->id . '.' . $logo->getClientOriginalExtension();
                                $destPath = public_path('/images/avatar_licnost');
                                $logo->move($destPath, $newavatarlicnostiName);

                                $fieldsToUpdate['avatar'] = $newavatarlicnostiName;
                            }

                            $old_licnost->update($fieldsToUpdate);
                        }
                    }
                }
            }

            // Izbriši licnosti koje je user izbrisao
            SchoolMember::where('school_id', $school->id)
                ->whereNotIn('id', $oldIds)
                ->delete();

            return true;
        } else {
            SchoolMember::where('school_id', $school->id)
                ->delete();

            return true;
        }
    }

    public function updateHistory(Request $request, School $school){

        $updateHistory = $school->update([
            'history' => $request->get('history')
        ]);

        if($updateHistory) {
            return $updateHistory;
        }

        return null;
    }

    public function updateTrophies(Request $request, School $school) {
        $oldIds = [];

        if($request->filled('nagrada')){
            foreach($request->get('nagrada') as $key => $nagrada){
                if($nagrada){
                    if(!array_key_exists('id', $nagrada)) {
                        $new_nagrada = Trophy::create([
                            'type' => $nagrada['vrsta'],
                            'place' => $nagrada['tip'],
                            'competition_name' => $nagrada['takmicenje'],
                            'level_of_competition' =>  $nagrada['nivo'],
                            'season' =>  $nagrada['sezona'],
                            'school_id' => $school->id
                        ]);

                        $oldIds[] = $new_nagrada->id;
                    } else {
                        $old_nagrada = Trophy::where('id', $nagrada['id'])->where('school_id', $school->id)->first();

                        if($old_nagrada) {
                            $oldIds[] = $old_nagrada->id;

                            $old_nagrada->update([
                                'type' => $nagrada['vrsta'],
                                'place' => $nagrada['tip'],
                                'competition_name' => $nagrada['takmicenje'],
                                'level_of_competition' =>  $nagrada['nivo'],
                                'season' =>  $nagrada['sezona'],
                            ]);
                        }
                    }
                }
            }

            // Izbriši trofeje koje je user izbrisao
            Trophy::where('school_id', $school->id)
                ->whereNotIn('id', $oldIds)
                ->delete();

            return true;
        } else {
            Trophy::where('school_id', $school->id)
                ->delete();

            return true;
        }
    }

    public function updateGallery(Request $request, School $school) {
        if($request->file('galerija')){
            $galerije = $request->file('galerija');
            foreach($galerije as $key => $slika){
                $newgalName = $key . '-' .time() . '-' .  $school->id . '.' . $slika->getClientOriginalExtension();
                $destPath = public_path('/images/galerija_skola');
                $slika->move($destPath, $newgalName);

                Gallery::create([
                    'image' => $newgalName,
                    'school_id' => $school->id
                ]);
            }

            return true;
        }

        return null;
    }

    public function getAllApproved() {
        return $this->model
            ->with(['user', 'category', 'sport'])
            ->where('status', 'active')
            ->get();
    }

    public function getAllNotApproved() {
        return $this->model
            ->with(['user', 'category', 'sport'])
            ->where('status', '!=', 'deleted')
            ->where('status', '!=', 'active')
            ->get();
    }
}