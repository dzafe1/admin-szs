<?php

namespace App\Http\Controllers;

use App\Repositories\AssociationRepository;
use App\Repositories\ClubRepository;
use App\Repositories\RegionRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\SportRepository;
use App\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class SchoolController extends Controller
{
    protected $regionRepository;
    protected $sportRepository;
    protected $clubRepository;
    protected $schoolRepository;

    /**
     * Create a new controller instance.
     *
     * @param RegionRepository $regionRepository
     * @param SportRepository $sportRepository
     * @param ClubRepository $clubRepository
     * @param SchoolRepository $schoolRepository
     */
    public function __construct(RegionRepository $regionRepository, SportRepository $sportRepository, ClubRepository $clubRepository, SchoolRepository $schoolRepository)
    {
        $this->regionRepository = $regionRepository;
        $this->sportRepository = $sportRepository;
        $this->clubRepository = $clubRepository;
        $this->schoolRepository = $schoolRepository;
    }

    public function approvedSchoolsList() {
        $schools = $this->schoolRepository
            ->getAllApproved();

        return view('skole', compact('schools'));
    }

    public function notApprovedSchoolsList() {
        $schools = $this->schoolRepository
            ->getAllNotApproved();

        return view('uskole', compact('schools'));
    }

    public function index_show()
    {
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

        $query = School::query();
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

        return view('schools.index', compact('clubCategories', 'sports', 'regions', 'results'));
    }

    public function displayAddSchool(){

        $regions = $this->regionRepository->getAll();
        $sports = $this->sportRepository->getAll();
        $clubCategories = $this->clubRepository->getSportCategories();

        return view('schools.new', compact('regions', 'sports', 'clubCategories'));

    }

    public function createSchool(Request $data) {
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
            'phone_1' => 'nullable|max:50|string',
            'phone_2' => 'nullable|max:50|string',
            'fax' => 'nullable|max:50|string',
            'email' => 'nullable|max:255|email',
            'website' => 'nullable|max:255|string',
            'address' => 'nullable|max:255|string',
            'pioniri' => 'required|boolean',
            'kadeti' => 'required|boolean',
            'juniori' => 'required|boolean',
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
            return redirect('schools/new')
                ->withErrors($validator)
                ->withInput();
        } else {
            $createSchool = $this->schoolRepository
                ->createSchool($data);

            if($createSchool) {
                flash()->overlay('Uspješno ste dodali novu školu.', 'Čestitamo');
                return redirect('/schools/' . $createSchool->id);
            }
        }
    }

    public function showSchool($id){
        $school = $this->schoolRepository
            ->getById($id);

        if($school) {
            $regions = collect();
            $currentRegion = $school->region;
            while ($currentRegion) {
                $regions->put(strtolower($currentRegion->region_type->type), $currentRegion->name);

                $currentRegion = $currentRegion->parent_region;
            }

            $school->setAttribute('regions', $regions);

            return view('schools.profile', compact('school'));
        }

        abort(404);
    }

    public function displayEditSchool($id) {
        $regions = $this->regionRepository->getAll();
        $sports = $this->sportRepository->getAll();
        $clubCategories = $this->clubRepository->getSportCategories();

        $school = $this->schoolRepository
            ->getById($id);

        if($school) {
            $clubRegions = collect();
            $currentRegion = $school->region;
            while ($currentRegion) {
                $clubRegions->put(strtolower($currentRegion->region_type->type), $currentRegion->id);

                $currentRegion = $currentRegion->parent_region;
            }

            $school->setAttribute('regions', $clubRegions);
            return view('schools.edit', compact('school', 'regions', 'sports', 'clubCategories'));
        }

        abort(404);
    }

    public function editSchoolGeneral($id, Request $request) {
        $school = $this->schoolRepository
            ->getById($id);

        if(!$school) {
            abort(404);
        }

        // Provjera da li je user napravio skolu
        $isOwner = $school->user->id == Auth::user()->id;
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
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
            'phone_1' => 'nullable|max:50|string',
            'phone_2' => 'nullable|max:50|string',
            'fax' => 'nullable|max:50|string',
            'email' => 'nullable|max:255|email',
            'website' => 'nullable|max:255|string',
            'address' => 'nullable|max:255|string',
            'pioniri' => 'required|boolean',
            'kadeti' => 'required|boolean',
            'juniori' => 'required|boolean',
            'facebook' => 'nullable|max:255|string',
            'instagram' => 'nullable|max:255|string',
            'twitter' => 'nullable|max:255|string',
            'youtube' => 'nullable|max:255|string',
            'video' => 'nullable|max:255|string'
        ]);

        if($validator->fails()){
            return redirect('/schools/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {

            $updateSchoolGeneral = $this->schoolRepository
                ->updateGeneral($request, $school);

            if($updateSchoolGeneral) {
                flash()->overlay('Uspješno ste izmjenili "Općenito" sekciju škole sporta.', 'Čestitamo');
                return back();
            }
        }

    }

    public function editSchoolStaff($id, Request $request) {
        $school = $this->schoolRepository
            ->getById($id);

        if(!$school) {
            abort(404);
        }

        // Provjera da li je user napravio skolu
        $isOwner = $school->user->id == Auth::user()->id;
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'licnost' => 'array',
            'licnost.*' => 'array',
            'licnost.*.avatar' => 'image|dimensions:min_width=312,min_height=250,max_width=1920,max_height=1080',
            'licnost.*.ime' => 'required|max:255|string',
            'licnost.*.prezime' => 'required|max:255|string',
            'licnost.*.opis' => 'nullable|max:1000|string',
        ]);

        if($validator->fails()){
            return redirect('/schools/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {

            $updateSchoolStaff = $this->schoolRepository
                ->updateStaff($request, $school);

            if($updateSchoolStaff) {
                flash()->overlay('Uspješno ste izmjenili članove škole sporta.', 'Čestitamo');
                return back();
            }
        }
    }

    public function editSchoolHistory($id, Request $request) {
        $school = $this->schoolRepository
            ->getById($id);

        if(!$school) {
            abort(404);
        }

        // Provjera da li je user napravio skolu
        $isOwner = $school->user->id == Auth::user()->id;
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'history' => 'nullable|string'
        ]);

        if($validator->fails()){
            return redirect('/schools/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {

            $updateSchoolHistory = $this->schoolRepository
                ->updateHistory($request, $school);

            if($updateSchoolHistory) {
                flash()->overlay('Uspješno ste izmjenili historiju škole sporta.', 'Čestitamo');
                return back();
            }
        }
    }

    public function editSchoolTrophies($id, Request $request) {
        $school = $this->schoolRepository
            ->getById($id);

        if(!$school) {
            abort(404);
        }

        // Provjera da li je user napravio skolu
        $isOwner = $school->user->id == Auth::user()->id;
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
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
            return redirect('/schools/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {

            $updateSchoolTrophies = $this->schoolRepository
                ->updateTrophies($request, $school);

            if($updateSchoolTrophies) {
                flash()->overlay('Uspješno ste izmjenili trofeje/nagrade škole sporta.', 'Čestitamo');
                return back();
            }
        }
    }

    public function editSchoolGallery($id, Request $request) {
        $school = $this->schoolRepository
            ->getById($id);

        if(!$school) {
            abort(404);
        }

        // Provjera da li je user napravio skolu
        $isOwner = $school->user->id == Auth::user()->id;
        if(!$isOwner) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'galerija' => 'array',
            'galerija.*' => 'required|image'
        ]);

        if($validator->fails()){
            return redirect('/schools/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        } else {

            $updateSchoolGallery = $this->schoolRepository
                ->updateGallery($request, $school);

            if($updateSchoolGallery) {
                flash()->overlay('Uspješno ste izmjenili galeriju škole sporta.', 'Čestitamo');
                return back();
            }
        }
    }
}
