<?php

namespace App\Http\Controllers;

use App\Club;
use App\Objects;
use App\Player;
use App\School;
use App\Staff;
use App\User;
use App\Vijest;

class DashboardController extends Controller
{
    public function index(){
        $allUsers = User::where('izbrisano', false)->get()->count();
        $adminUsers = User::where('izbrisano', false)->where('isAdmin', true)->get()->count();
        $allPlayers = Player::count();
        $approvedPlayers = Player::where('status', 'active')->get()->count();
        $allObjects = Objects::count();
        $approvedObjects = Objects::where('status', 'active')->get()->count();
        $allClubs = Club::count();
        $approvedClubs = Club::where('status', 'active')->get()->count();
        $allSchools = School::count();
        $approvedSchools = School::where('status', 'active')->get()->count();
        $allStaff = Staff::count();
        $approvedStaff = Staff::where('status', 'active')->get()->count();
        $allNews = Vijest::where('izbrisano', false)->get()->count();
        $approvedNews = Vijest::where('izbrisano', false)->where('odobreno', true)->get()->count();

        $statistics = [
            [
                'label' => 'Korisnici',
                'all' => $allUsers,
                'sub' => $adminUsers,
                'sub_label' => 'Korisnika sa admin privilegijom',
                'bg' => 'primary'
            ],
            [
                'label' => 'Sportisti',
                'all' => $allPlayers,
                'sub' => $approvedPlayers,
                'sub_label' => 'Odobrenih profila sportista',
                'bg' => 'success'
            ],
            [
                'label' => 'Objekti',
                'all' => $allObjects,
                'sub' => $approvedObjects,
                'sub_label' => 'Odobrenih objekata',
                'bg' => 'grey'
            ],
            [
                'label' => 'Klubovi',
                'all' => $allClubs,
                'sub' => $approvedClubs,
                'sub_label' => 'Odobrenih klubova',
                'bg' => 'warning'
            ],
            [
                'label' => 'Škole sporta',
                'all' => $allSchools,
                'sub' => $approvedSchools,
                'sub_label' => 'Odobrenih škola sporta',
                'bg' => 'dark'
            ],
            [
                'label' => 'Sportski kadrovi',
                'all' => $allStaff,
                'sub' => $approvedStaff,
                'sub_label' => 'Odobrenih sportskih kadrova',
                'bg' => 'primary'
            ],
            [
                'label' => 'Vijesti',
                'all' => $allNews,
                'sub' => $approvedNews,
                'sub_label' => 'Odobrenih sportskih vijesti',
                'bg' => 'success'
            ],
        ];

        $statistics = json_decode(json_encode($statistics), FALSE);

        return view('dashboard', compact('statistics'));
    }
}