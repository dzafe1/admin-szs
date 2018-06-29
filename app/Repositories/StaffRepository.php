<?php

namespace App\Repositories;

use App\ClubRequest;
use App\Gallery;
use App\Language;
use App\Staff;
use App\StaffWorkHistory;
use App\Trophy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffRepository {
    protected $model;

    public function __construct(Staff $model){
        $this->model = $model;
    }

    public function getAll() {
        return $this->model
            ->limit(6)
            ->get();
    }

    public function createStaff(Request $request) {
        $newLogoName = 'default.png';

        if($request->file('avatar')){
            $logo = $request->file('avatar');
            $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
            $destinationPath = public_path('/images/staff_avatars');
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

        $attributesToInsert = [
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'avatar' => $newLogoName,
            'date_of_birth' => new Carbon($request->get('date_of_birth')),
            'facebook' => $request->get('facebook'),
            'twitter' => $request->get('twitter'),
            'instagram' => $request->get('instagram'),
            'youtube' => $request->get('youtube'),
            'video' => $request->get('video'),
            'region_id' => $region_id,
            'profession_id' => $request->get('profession'),
            'biography' => $request->get('biography'),
            'user_id' => Auth::user()->id,
            'city' => $request->get('city'),
            'education' => $request->get('education'),
            'additional_education' => $request->get('additional_education'),
            'number_of_certificates' => $request->get('number_of_certificates'),
            'number_of_projects' => $request->get('number_of_projects'),
            'work_experience' => $request->get('work_experience'),
        ];

        if($request->has('requested_club')) {
            $attributesToInsert['requested_club'] = $request->get('requested_club');
        } else {
            $attributesToInsert['club_name'] = $request->get('club_name');
        }

        $createStaff = $this->model->create($attributesToInsert);

        if($createStaff) {

                if($request->filled('requested_club')) {
                    ClubRequest::create([
                        'club_id' => $request->get('requested_club'),
                        'staff_id' => $createStaff->id
                    ]);
                }

                if($request->filled('history')){
                    foreach($request->get('history') as $key => $history){
                        if($history){
                            StaffWorkHistory::create([
                                'season' => $history['season'],
                                'club' => $history['club'],
                                'staff_id' => $createStaff->id
                            ]);
                        }
                    }
                }

                if($request->filled('languages')){
                    $createStaff->languages()->attach($request->get('languages'));
                }

                if($request->filled('nagrada')){
                    foreach($request->get('nagrada') as $key => $nagrada){
                        if($nagrada){
                            Trophy::create([
                                'type' => $nagrada['vrsta'],
                                'place' => $nagrada['tip'],
                                'competition_name' => $nagrada['takmicenje'],
                                'level_of_competition' =>  $nagrada['nivo'],
                                'season' =>  $nagrada['sezona'],
                                'staff_id' => $createStaff->id
                            ]);
                        }
                    }
                }

                if($request->file('galerija')){
                    $galerije = $request->file('galerija');
                    foreach($galerije as $key => $slika){
                        $newgalName = $key . '-' .time() . '-' .  $createStaff->id . '.' . $slika->getClientOriginalExtension();
                        $destPath = public_path('/images/galerija_kadrovi');
                        $slika->move($destPath, $newgalName);

                        Gallery::create([
                            'image' => $newgalName,
                            'staff_id' => $createStaff->id
                        ]);
                    }
                }

                return $createStaff;
        } else {
            $createStaff->delete();
            return null;
        }
    }

    public function getById($id) {
        return $this->model
            ->where('id', $id)
            ->with(['languages', 'profession', 'work_history', 'region', 'current_club', 'club', 'user', 'trophies', 'images'])
            ->first();
    }

    public function updateGeneral(Request $request, Staff $staff){
        $newLogoName = $staff->avatar;

        if($request->file('avatar')){
            $logo = $request->file('avatar');
            $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
            $destinationPath = config('general.image_paths.staff_images');
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

        $updateStaffGeneral = $staff->update([
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'profession' => $request->get('profession'),
            'avatar' => $newLogoName,
            'facebook' => $request->get('facebook'),
            'twitter' => $request->get('twitter'),
            'instagram' => $request->get('instagram'),
            'youtube' => $request->get('youtube'),
            'video' => $request->get('video'),
            'region_id' => $region_id,
            'city' => $request->get('city')
        ]);

        if($updateStaffGeneral) {
            return $updateStaffGeneral;
        }

        return null;
    }

    public function updateStatus(Request $request, Staff $staff) {

        $attributesToUpdate = [
            'date_of_birth' => new Carbon($request->get('date_of_birth')),
            'education' => $request->get('education'),
            'additional_education' => $request->get('additional_education'),
            'number_of_certificates' => $request->get('number_of_certificates'),
            'number_of_projects' => $request->get('number_of_projects'),
            'work_experience' => $request->get('work_experience')
        ];

        if($request->has('requested_club')) {
            if($staff->requested_club != $request->get('requested_club')) {
                // Delete previous requests and club
                ClubRequest::where('staff_id', $staff->id)
                    ->where('club_id', $request->get('requested_club'))
                    ->delete();

                ClubRequest::create([
                    'club_id' => $request->get('requested_club'),
                    'staff_id' => $staff->id
                ]);

                $attributesToUpdate['requested_club'] = $request->get('requested_club');
                $attributesToUpdate['club_id'] = null;
            }
        } else {
            $attributesToUpdate['club_name'] = $request->get('club_name');
            $attributesToUpdate['club_id'] = null;
        }

        $updateStaffStatus = $staff->update($attributesToUpdate);

        if ($updateStaffStatus) {
            $oldIds = [];

            if($request->filled('languages')){
                $staff->languages()->sync($request->get('languages'));
            }

            if ($request->filled('history')) {
                foreach ($request->get('history') as $key => $history) {
                    if ($history) {
                        if (!array_key_exists('id', $history)) {
                            $newClub = StaffWorkHistory::create([
                                'season' => $history['season'],
                                'club' => $history['club'],
                                'staff_id' => $staff->id
                            ]);

                            $oldIds[] = $newClub->id;
                        } else {
                            $oldClub = StaffWorkHistory::where('id', $history['id'])->where('staff_id', $staff->id)->first();

                            if ($oldClub) {
                                $oldIds[] = $oldClub->id;

                                $oldClub->update([
                                    'season' => $history['season'],
                                    'club' => $history['club'],
                                ]);
                            }
                        }
                    }
                }

                // Izbriši trofeje koje je user izbrisao
                StaffWorkHistory::where('staff_id', $staff->id)
                    ->whereNotIn('id', $oldIds)
                    ->delete();
            } else {
                StaffWorkHistory::where('staff_id', $staff->id)
                    ->delete();
            }

            return $updateStaffStatus;
        }

        return null;

    }

    public function updateBiography(Request $request, Staff $staff){

        $updateStaffBiography = $staff->update([
            'biography' => $request->get('biography')
        ]);

        if($updateStaffBiography) {
            return $updateStaffBiography;
        }

        return null;
    }

    public function updateTrophies(Request $request, Staff $staff) {
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
                            'staff_id' => $staff->id
                        ]);

                        $oldIds[] = $new_nagrada->id;
                    } else {
                        $old_nagrada = Trophy::where('id', $nagrada['id'])->where('staff_id', $staff->id)->first();

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
            Trophy::where('staff_id', $staff->id)
                ->whereNotIn('id', $oldIds)
                ->delete();

            return true;
        } else {
            Trophy::where('staff_id', $staff->id)
                ->delete();

            return true;
        }
    }

    public function updateGallery(Request $request, Staff $staff) {
        if($request->file('galerija')){
            $galerije = $request->file('galerija');
            foreach($galerije as $key => $slika){
                $newgalName = $key . '-' .time() . '-' .  $staff->id . '.' . $slika->getClientOriginalExtension();
                $destPath = config('general.image_paths.staff_gallery');
                $slika->move($destPath, $newgalName);

                Gallery::create([
                    'image' => $newgalName,
                    'staff_id' => $staff->id
                ]);
            }

            return true;
        }

        return null;
    }

    public function getAllApproved() {
        return $this->model
            ->with(['profession', 'current_club', 'user'])
            ->where('status', 'active')
            ->get();
    }

    public function getAllNotApproved() {
        return $this->model
            ->with(['profession', 'current_club', 'user'])
            ->where('status', '!=', 'deleted')
            ->where('status', '!=', 'active')
            ->get();
    }
}
