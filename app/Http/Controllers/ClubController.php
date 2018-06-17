<?php

namespace App\Http\Controllers;

use App\ClubRequest;
use App\ClubStaff;
use App\Gallery;
use App\History;
use App\Player;
use App\Repositories\AssociationRepository;
use App\Repositories\ClubRepository;
use App\Repositories\RegionRepository;
use App\Repositories\SportRepository;
use App\Staff;
use App\Trophy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Club;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ClubController extends Controller
{
    protected $regionRepository;
    protected $sportRepository;
    protected $clubRepository;
    protected $associationRepository;

    /**
     * Create a new controller instance.
     *
     * @param RegionRepository $regionRepository
     * @param SportRepository $sportRepository
     * @param ClubRepository $clubRepository
     * @param AssociationRepository $associationRepository
     */
    public function __construct(RegionRepository $regionRepository, SportRepository $sportRepository, ClubRepository $clubRepository, AssociationRepository $associationRepository)
    {
        $this->middleware('auth')->except(['club_show', 'index_show']);
        $this->regionRepository = $regionRepository;
        $this->sportRepository = $sportRepository;
        $this->clubRepository = $clubRepository;
        $this->associationRepository = $associationRepository;
    }

    public function approvedClubsList() {
        $clubs = $this->clubRepository
            ->getAllApproved();

        return view('klubovi', compact('clubs'));
    }

    public function notApprovedClubsList() {
        $clubs = $this->clubRepository
            ->getAllNotApproved();

        return view('uklubovi', compact('clubs'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function new_show()
    {
        $regions = $this->regionRepository->getAll();
        $sports = $this->sportRepository->getAll();
        $clubCategories = $this->clubRepository->getSportCategories();
        $associations = $this->associationRepository->getAll();

        return view('clubs.new', compact('regions', 'sports', 'clubCategories', 'associations'));
    }

    public function clubs_add(){
        return view('clubs.add');
    }

    public function index_show(Request $data){
        $clubCategories = $this->clubRepository
            ->getSportCategories();

        $sports = $this->sportRepository
            ->getAll();

        $regions = $this->regionRepository
            ->getAll();

        // Algoritam za dobijanje children ids od regija
        $region_ids = [];
        $province_filled = Input::filled('province');
        $region_filled = Input::filled('region');
        $municipality_filled = Input::filled('municipality');

        if($province_filled) {
            if(!$region_filled) {
                $region_ids[] = Input::get('province');
                $province = $this->regionRepository
                    ->getById(Input::get('province'));

                if($province) {
                    foreach ($province->child_regions as $region) {
                        $region_ids[] = $region->id;

                        foreach ($region->child_regions as $municipality) {
                            $region_ids[] = $municipality->id;
                        }
                    }
                }
            } else {
                $region_ids[] = Input::get('region');

                if($municipality_filled) {
                    $region_ids[] = Input::get('municipality');
                } else {
                    $region = $this->regionRepository
                        ->getById(Input::get('region'));

                    if($region) {
                        foreach ($region->child_regions as $region) {
                            $region_ids[] = $region->id;
                        }
                    }
                }
            }
        }

        $query = Club::query();
        if(Input::filled('category')){
            $query->where('club_category_id', Input::get('category'));
        }

        if(Input::filled('sport')){
            $query->where('sport_id', Input::get('sport'));
        }

        if(!empty($region_ids)){
            $query->whereIn('region_id', $region_ids);
        }

        if(Input::filled('sort')){
            $sort = Input::get('sort');
            if($sort === 'name_desc') {
                $query->orderBy('name', 'DESC');
            } else if($sort === 'name_asc') {
                $query->orderBy('name', 'ASC');
            } else if($sort === 'sport') {
                $query->whereHas('sport', function ($query) {
                    $query->orderBy('sports.name', 'DESC');
                });
            }
        }

        $results = $query
            ->paginate(16);

        return view('clubs.index', compact('clubCategories', 'sports', 'regions', 'results'));
    }

    public function club_show($id){
        $club = Club::with(['histories','trophies','club_staff','images','creator','association','region'])
            ->where('id', $id)
            ->first();


        if($club) {
            $regions = collect();
            $currentRegion = $club->region;
            while ($currentRegion) {
                $regions->put(strtolower($currentRegion->region_type->type), $currentRegion->name);

                $currentRegion = $currentRegion->parent_region;
            }

            $club->setAttribute('regions', $regions);

            $players = $club->players()->paginate(12);
            $staff = $club->staff()->paginate(12);

            return view('clubs.profile', compact('club','players', 'staff'));
        }

        abort(404);
    }

    public function new(Request $data){
    	$validator = Validator::make($data->all(),[
    	    'logo' => 'required|image|dimensions:min_width=200,min_height=200,max_width=1024,max_height=1024',
    		'name' => 'required|max:255|string',
    		'nature' => 'required|max:255|string',
    		'continent' => 'required|integer|exists:regions,id',
    		'country' => 'required|integer|exists:regions,id',
    		'province' => 'integer|exists:regions,id',
    		'region' => 'integer|exists:regions,id',
    		'municipality' => 'integer|exists:regions,id',
    		'city' => 'required|max:255|string',
    		'type' => 'required|integer',
    		'sport' => 'required|integer|exists:sports,id',
    		'category' => 'required|integer|exists:club_categories,id',
    		'established_in' => 'nullable|digits:4|integer|min:1800|max:'.date('Y'),
    		'home_field' => 'nullable|max:255|string',
    		'competition' => 'nullable|max:255|string',
    		'association' => 'nullable|integer|exists:associations,id',
    		'phone_1' => 'nullable|max:50|string',
            'phone_2' => 'nullable|max:50|string',
            'fax' => 'nullable|max:50|string',
            'email' => 'nullable|max:255|email',
            'website' => 'nullable|max:255|string',
    		'address' => 'nullable|max:255|string',
            'facebook' => 'nullable|max:255|string',
            'instagram' => 'nullable|max:255|string',
            'twitter' => 'nullable|max:255|string',
            'youtube' => 'nullable|max:255|string',
            'video' => 'nullable|max:255|string',
            'history' => 'nullable|string',
            // Licnosti
            'licnost' => 'array',
            'licnost.*' => 'array',
            'licnost.*.avatar' => 'image|dimensions:min_width=312,min_height=250,max_width=1920,max_height=1080',
            'licnost.*.ime' => 'required|max:255|string',
            'licnost.*.prezime' => 'required|max:255|string',
            'licnost.*.opis' => 'nullable|max:1000|string',
            // Nagrade
            'nagrada' => 'array',
            'nagrada.*' => 'array',
            'nagrada.*.vrsta' => 'required|max:255|string|in:Medalja,Trofej/Pehar,Priznanje,Plaketa',
            'nagrada.*.tip' => 'required|max:255|string|in:Zlato,Srebro,Bronza,Ostalo',
            'nagrada.*.nivo' => 'required|max:255|string|in:Internacionalni nivo,Regionalni nivo,Državni nivo,Entitetski nivo,Drugo',
            'nagrada.*.takmicenje' => 'required|max:255|string',
            'nagrada.*.sezona' => 'required|digits:4|integer|min:1800|max:'.date('Y'),
            'nagrada.*.osvajanja' => 'nullable|integer',
            // Slike
            'galerija' => 'array',
            'galerija.*' => 'required|image',
    	]);

    	if ($validator->fails()) {
    		return redirect('clubs/new')
    					->withErrors($validator)
    					->withInput();
    	} else {
    		if($data->file('logo')){
    			$logo = $data->file('logo');
    			$newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
    			$destinationPath = public_path('/images/club_logo');
    			$logo->move($destinationPath, $newLogoName);
    		} else {
                $newLogoName = 'default.png';
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

            $club_id = Club::insertGetId([
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
                'facebook' => $data->get('facebook'),
                'twitter' => $data->get('twitter'),
                'instagram' => $data->get('instagram'),
                'youtube' => $data->get('youtube'),
                'video' => $data->get('video'),
                'association_id' => $data->get('association'),
                'club_category_id' => $data->get('category'),
                'sport_id' => $data->get('sport'),
                'region_id' => $region_id,
                'user_id' => Auth::user()->id,
                'created_at' => new Carbon(),
                'updated_at' => new Carbon(),
            ]);

    		if(!empty($club_id)){
                if($data->filled('history')){
                    History::create([
                        'content' => $data->get('history'),
                        'club_id' => $club_id
                    ]);
                }

                if($data->filled('nagrada')){
                    foreach($data->get('nagrada') as $key => $nagrada){
                        if($nagrada){
                            Trophy::create([
                                'type' => $data['nagrada'][$key]['vrsta'],
                                'place' => $data['nagrada'][$key]['tip'],
                                'competition_name' => $data['nagrada'][$key]['takmicenje'],
                                'level_of_competition' =>  $data['nagrada'][$key]['nivo'],
                                'season' =>  $data['nagrada'][$key]['sezona'],
                                'club_id' => $club_id
                            ]);
                        }
                    }
                }

                if($data->filled('licnost')){
                    foreach($data->get('licnost') as $key => $licnost){
                        if($licnost){
                            $logo = array_key_exists('avatar', $data['licnost'][$key]) ? $data['licnost'][$key]['avatar'] : null;

                            if($logo) {
                                $newavatarlicnostiName = time() . '-' . $club_id . '.' . $logo->getClientOriginalExtension();
                                $destPath = public_path('/images/avatar_licnost');
                                $logo->move($destPath, $newavatarlicnostiName);
                            } else {
                                $newavatarlicnostiName = 'default_avatar.png';
                            }

                            ClubStaff::create([
                                'avatar' => $newavatarlicnostiName,
                                'firstname' => $data['licnost'][$key]['ime'],
                                'lastname' => $data['licnost'][$key]['prezime'],
                                'biography' => $data['licnost'][$key]['opis'] ? $data['licnost'][$key]['opis'] : null,
                                'club_id' => $club_id
                            ]);
                        }
                    }
                }

                if($data->file('galerija')){
                    $galerije = $data->file('galerija');
                    foreach($galerije as $key => $slika){
                        $newgalName = $key . '-' .time() . '-' .  $club_id . '.' . $slika->getClientOriginalExtension();
                        $destPath = public_path('/images/galerija_klub');
                        $slika->move($destPath, $newgalName);

                        Gallery::create([
                            'image' => $newgalName,
                            'club_id' => $club_id
                        ]);
                    }
                }

                flash()->overlay('Uspješno ste dodali klub.', 'Čestitamo');
                return redirect('/clubs/' . $club_id);
            }

    	}

        return redirect('/clubs/new');
    }

    public function edit_club_show($id){

        $regions = $this->regionRepository->getAll();
        $sports = $this->sportRepository->getAll();
        $clubCategories = $this->clubRepository->getSportCategories();
        $associations = $this->associationRepository->getAll();

        $club = Club::with(['histories','trophies','club_staff','images','creator','association','region','category','association'])
            ->where('id', $id)
            ->first();

        if($club) {
            $clubRegions = collect();
            $currentRegion = $club->region;
            while ($currentRegion) {
                $clubRegions->put(strtolower($currentRegion->region_type->type), $currentRegion->id);

                $currentRegion = $currentRegion->parent_region;
            }

            $club->setAttribute('regions', $clubRegions);
            return view('clubs.edit', compact('club', 'regions', 'sports', 'clubCategories', 'associations'));
        }

        abort(404);

    }

    public function edit_club(Request $data, $id){
        // Provjera da li je user vlasnik kluba
        $isOwner = Club::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($data->all(),[
            'logo' => 'image|dimensions:min_width=200,min_height=200,max_width=1024,max_height=1024',
            'name' => 'required|max:255|string',
            'nature' => 'required|max:255|string',
            'continent' => 'required|integer|exists:regions,id',
            'country' => 'required|integer|exists:regions,id',
            'province' => 'integer|exists:regions,id',
            'region' => 'integer|exists:regions,id',
            'municipality' => 'integer|exists:regions,id',
            'city' => 'required|max:255|string',
            'type' => 'required|integer',
            'sport' => 'required|integer|exists:sports,id',
            'category' => 'required|integer|exists:club_categories,id',
            'established_in' => 'nullable|digits:4|integer|min:1800|max:'.date('Y'),
            'home_field' => 'nullable|max:255|string',
            'competition' => 'nullable|max:255|string',
            'association' => 'nullable|integer|exists:associations,id',
            'phone_1' => 'nullable|max:50|string',
            'phone_2' => 'nullable|max:50|string',
            'fax' => 'nullable|max:50|string',
            'email' => 'nullable|max:255|email',
            'website' => 'nullable|max:255|string',
            'address' => 'nullable|max:255|string',
            'facebook' => 'nullable|max:255|string',
            'instagram' => 'nullable|max:255|string',
            'twitter' => 'nullable|max:255|string',
            'youtube' => 'nullable|max:255|string',
            'video' => 'nullable|max:255|string'
        ]);

        if($validator->fails()){
            return redirect('/clubs/' . $id . '/edit')
                        ->withErrors($validator)
                        ->withInput();
        } else {
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

            $fieldsToUpdate = [
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
                'facebook' => $data->get('facebook'),
                'twitter' => $data->get('twitter'),
                'instagram' => $data->get('instagram'),
                'youtube' => $data->get('youtube'),
                'video' => $data->get('video'),
                'association_id' => $data->get('association'),
                'club_category_id' => $data->get('category'),
                'sport_id' => $data->get('sport'),
                'region_id' => $region_id,
                'updated_at' => new Carbon(),
            ];

            if($data->file('logo')){
                $logo = $data->file('logo');
                $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
                $destinationPath = public_path('/images/club_logo');
                $logo->move($destinationPath, $newLogoName);

                $fieldsToUpdate['logo'] = $newLogoName;
            }

            $updateClub = Club::where('id', $id)
                ->update($fieldsToUpdate);

            if($updateClub) {
                flash()->overlay('Uspješno ste editovali "Općenito" sekciju kluba.', 'Čestitamo');
                return redirect('/clubs/' . $id . '/edit');
            }

            abort(404);
        }
    }

    public function edit_licnost(Request $data, $id){

        // Provjera da li je user vlasnik kluba
        $isOwner = Club::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }


        $validator = Validator::make($data->all(), [
            'licnost' => 'array',
            'licnost.*' => 'array',
            'licnost.*.avatar' => 'image|dimensions:min_width=312,min_height=250,max_width=1920,max_height=1080',
            'licnost.*.ime' => 'required|max:255|string',
            'licnost.*.prezime' => 'required|max:255|string',
            'licnost.*.opis' => 'nullable|max:1000|string',
        ]);

        if($validator->fails()){
            return redirect('clubs/' . $id . '/edit')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            $oldIds = [];

            if($data->filled('licnost')){
                foreach($data->get('licnost') as $key => $licnost){
                    if($licnost){
                        $logo = array_key_exists('avatar', $data['licnost'][$key]) ? $data['licnost'][$key]['avatar'] : null;
                        $newavatarlicnostiName = null;
                        // Ako nema id dodaj licnost
                        if(!array_key_exists('id', $licnost)) {
                            if ($logo) {
                                $newavatarlicnostiName = time() . '-' . $id . '.' . $logo->getClientOriginalExtension();
                                $destPath = public_path('/images/avatar_licnost');
                                $logo->move($destPath, $newavatarlicnostiName);
                            } else {
                                $newavatarlicnostiName = 'default_avatar.png';
                            }

                            $new_licnost = ClubStaff::create([
                                'avatar' => $newavatarlicnostiName,
                                'firstname' => $data['licnost'][$key]['ime'],
                                'lastname' => $data['licnost'][$key]['prezime'],
                                'biography' => $data['licnost'][$key]['opis'] ? $data['licnost'][$key]['opis'] : null,
                                'club_id' => $id
                            ]);

                            $oldIds[] = $new_licnost->id;
                        } else {
                            $old_licnost = ClubStaff::where('id', $licnost['id'])->where('club_id', $id)->first();

                            if($old_licnost) {
                                $oldIds[] = $old_licnost->id;

                                $fieldsToUpdate = [
                                    'firstname' => $data['licnost'][$key]['ime'],
                                    'lastname' => $data['licnost'][$key]['prezime'],
                                    'biography' => $data['licnost'][$key]['opis'] ? $data['licnost'][$key]['opis'] : null,
                                ];

                                if ($logo) {
                                    $newavatarlicnostiName = time() . '-' . $id . '.' . $logo->getClientOriginalExtension();
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
                ClubStaff::where('club_id', $id)
                    ->whereNotIn('id', $oldIds)
                    ->delete();

                flash()->overlay('Uspješno ste editovali istaknute ličnosti kluba.', 'Čestitamo');
                return redirect('clubs/' . $id . '/edit');
            } else {
                ClubStaff::where('club_id', $id)
                    ->delete();

                flash()->overlay('Uspješno ste editovali istaknute ličnosti kluba.', 'Čestitamo');
                return redirect('clubs/' . $id . '/edit');
            }
        }
    }

    public function edit_vremeplov(Request $data, $id){

        // Provjera da li je user vlasnik kluba
        $history = History::where('id', $id)->first();
        $isOwner = Club::where('id', $history->club_id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($data->all(),[
            'history' => 'nullable|string',
        ]);

        if($validator->fails()){
            return redirect('clubs/' . $history->club_id . '/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else{
            $updateHistory = $history
                ->update([
                    'content' => $data->history
                ]);

            if($updateHistory) {
                flash()->overlay('Uspješno ste editovali historiju kluba.', 'Čestitamo');
                return redirect('clubs/' . $history->club_id . '/edit');
            }
        }
    }

    public function add_vremeplov(Request $data, $club_id){

        // Provjera da li je user vlasnik kluba
        $isOwner = Club::where('id', $club_id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($data->all(),[
            'history' => 'nullable|string',
        ]);

        if($validator->fails()){
            return redirect('clubs/' . $club_id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }else{
            $addHistory = History::create([
                    'content' => $data->history,
                    'club_id' => $club_id
                ]);

            if($addHistory) {
                flash()->overlay('Uspješno ste editovali historiju kluba.', 'Čestitamo');
                return redirect('clubs/' . $club_id . '/edit');
            }
        }
    }

    public function edit_trofej(Request $data, $id){
        // Provjera da li je user vlasnik kluba
        $isOwner = Club::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($data->all(),[
            'nagrada' => 'array',
            'nagrada.*' => 'array',
            'nagrada.*.vrsta' => 'required|max:255|string|in:Medalja,Trofej/Pehar,Priznanje,Plaketa',
            'nagrada.*.tip' => 'required|max:255|string|in:Zlato,Srebro,Bronza,Ostalo',
            'nagrada.*.nivo' => 'required|max:255|string|in:Internacionalni nivo,Regionalni nivo,Državni nivo,Entitetski nivo,Drugo',
            'nagrada.*.takmicenje' => 'required|max:255|string',
            'nagrada.*.sezona' => 'required|digits:4|integer|min:1800|max:'.date('Y'),
            'nagrada.*.osvajanja' => 'nullable|integer',
        ]);

        if($validator->fails()){
            return redirect('clubs/' . $id . '/edit')
                        ->withErrors($validator)
                        ->withInput();
        } else {
            $oldIds = [];

            if($data->filled('nagrada')){
                foreach($data->get('nagrada') as $key => $nagrada){
                    if($nagrada){
                        if(!array_key_exists('id', $nagrada)) {
                            $new_nagrada = Trophy::create([
                                'type' => $data['nagrada'][$key]['vrsta'],
                                'place' => $data['nagrada'][$key]['tip'],
                                'competition_name' => $data['nagrada'][$key]['takmicenje'],
                                'level_of_competition' =>  $data['nagrada'][$key]['nivo'],
                                'season' =>  $data['nagrada'][$key]['sezona'],
                                'club_id' => $id
                            ]);

                            $oldIds[] = $new_nagrada->id;
                        } else {
                            $old_nagrada = Trophy::where('id', $nagrada['id'])->where('club_id', $id)->first();

                            if($old_nagrada) {
                                $oldIds[] = $old_nagrada->id;

                                $old_nagrada->update([
                                    'type' => $data['nagrada'][$key]['vrsta'],
                                    'place' => $data['nagrada'][$key]['tip'],
                                    'competition_name' => $data['nagrada'][$key]['takmicenje'],
                                    'level_of_competition' =>  $data['nagrada'][$key]['nivo'],
                                    'season' =>  $data['nagrada'][$key]['sezona'],
                                ]);
                            }
                        }
                    }
                }

                // Izbriši trofeje koje je user izbrisao
                Trophy::where('club_id', $id)
                    ->whereNotIn('id', $oldIds)
                    ->delete();

                flash()->overlay('Uspješno ste editovali nagrade/trofeje kluba.', 'Čestitamo');
                return redirect('clubs/' . $id . '/edit');
            } else {
                Trophy::where('club_id', $id)
                    ->delete();

                flash()->overlay('Uspješno ste editovali nagrade/trofeje kluba.', 'Čestitamo');
                return redirect('clubs/' . $id . '/edit');
            }
        }
    }

    public function edit_galerija(Request $data, $id){
        // Provjera da li je user vlasnik kluba
        $isOwner = Club::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($data->all(),[
            'galerija' => 'array',
            'galerija.*' => 'required|image',
        ]);

        if ($validator->fails()) {
            return redirect('clubs/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {
            if($data->file('galerija')){
                $galerije = $data->file('galerija');
                foreach($galerije as $key => $slika){
                    $newgalName = $key . '-' .time() . '-' .  $id . '.' . $slika->getClientOriginalExtension();
                    $destPath = public_path('/images/galerija_klub');
                    $slika->move($destPath, $newgalName);

                    Gallery::create([
                        'image' => $newgalName,
                        'club_id' => $id
                    ]);
                }
            }

            flash()->overlay('Uspješno ste editovali galeriju kluba.', 'Čestitamo');
            return redirect('clubs/' . $id . '/edit');
        }
    }

    public function approvePlayer($id, $player_id, $notify_id) {
        // Provjera da li je user vlasnik kluba
        $isOwner = Club::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }

        $player = Player::find($player_id);

        if(!$player) {
            abort(404);
        }

        $request = ClubRequest::find($notify_id);

        $isApproved = false;

        if($request) {
            if(!$request->approved){
                $approveRequest = $request->update([
                    'approved' => true
                ]);
                $isApproved = true;
            } else {
                $approveRequest = $request->update([
                    'approved' => false
                ]);
            }

            if ($approveRequest) {
                if($isApproved) {
                    $updatePlayer = $player->update([
                        'club_id' => $id
                    ]);
                } else {
                    $updatePlayer = $player->update([
                        'club_id' => null
                    ]);
                }

                if ($updatePlayer) {
                    if($isApproved) {
                        flash()->overlay('Uspješno ste prihvatili zahtjev sportiste za pridruživanje klubu.', 'Čestitamo');
                    } else {
                        flash()->overlay('Uspješno ste vratili na čekanje zahtjev sportiste za pridruživanje klubu.', 'Čestitamo');
                    }
                    return redirect('me/notifications');
                }
            }
        }
    }

    public function approveStaff($id, $staff_id, $notify_id) {
        // Provjera da li je user vlasnik kluba
        $isOwner = Club::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if(!$isOwner) {
            abort(404);
        }

        $staff = Staff::find($staff_id);

        if(!$staff) {
            abort(404);
        }

        $request = ClubRequest::find($notify_id);

        $isApproved = false;

        if($request) {
            if(!$request->approved){
                $approveRequest = $request->update([
                    'approved' => true
                ]);
                $isApproved = true;
            } else {
                $approveRequest = $request->update([
                    'approved' => false
                ]);
            }

            if ($approveRequest) {
                if($isApproved) {
                    $updateStaff = $staff->update([
                        'club_id' => $id
                    ]);
                } else {
                    $updateStaff = $staff->update([
                        'club_id' => null
                    ]);
                }

                if ($updateStaff) {
                    if($isApproved) {
                        flash()->overlay('Uspješno ste prihvatili zahtjev stručnog kadra za pridruživanje klubu.', 'Čestitamo');
                    } else {
                        flash()->overlay('Uspješno ste vratili na čekanje zahtjev stručnog kadra za pridruživanje klubu.', 'Čestitamo');
                    }
                    return redirect('me/notifications');
                }
            }
        }
    }
}
