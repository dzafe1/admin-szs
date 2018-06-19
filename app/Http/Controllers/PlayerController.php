<?php

namespace App\Http\Controllers;

use App\Club;
use App\Player;
use App\Repositories\ClubRepository;
use App\Repositories\PlayerRepository;
use App\Repositories\RegionRepository;
use App\Repositories\SportRepository;
use App\Sport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    protected $sportRepository;
    protected $playerRepository;
    protected $regionRepository;
    protected $clubRepository;

    protected $allAttributesInputs = [
        'preferred_leg' => 'label:Primarna noga|type:select|name:preferred_leg|options:Desna noga,Lijeva noga,Obje|default:Izaberite primarnu nogu sportiste',
        'preferred_arm' => 'label:Primarna ruka|type:select|name:preferred_arm|options:Desna ruka,Lijeva ruka,Obje|default:Izaberite primarnu ruku sportiste',
        'rank' => 'label:Rank|type:input|name:rank|placeholder:Unesite trenutni rank sportiste',
        'discipline' => 'label:Disciplina|type:select|name:discipline|options:Kratke pruge,Srednje pruge,Duge pruge,Štafete,Preponske utrke,Brzo hodanje,Koplje,Disk,Kugla,Kladivo,Skok u dalj,Skok s motkom,Troskok,Sedmoboj (žene),Desetoboj (muškarci)|skijanje_options:Slalom,Veleslalom,Spust,Super-veleslalom,Alpska kombinacija,Paralelna natjecanja|default:Izaberite disciplinu sportiste',
        'best_result' => 'label:Najbolji rezultat|type:input|name:best_result|placeholder:Unesite najbolji rezultat sportiste',
        'agent' => 'label:Agent|type:input|name:agent|placeholder:Unesite ime agenta sportiste',
        'position' => 'label:Pozicija|type:input|name:position|placeholder:Unesite poziciju sportiste',
        'competition' => 'label:Takmičenje|type:input|name:competition|placeholder:Unesite ime takmičenja u kojem sportista nastupa',
        //'federation' => 'label:Federacija|type:input|name:federation|placeholder:Unesite trenutni rank sportiste',
        'category' => 'label:Kategorija|type:select|name:category|options:Drumski biciklizam,Brdski biciklizam,BMX|karate_options:Kihon,Kate,Kumite|default:Izaberite kategoriju u kojoj se sportista natječe',
        'market_value' => 'label:Vrijednost|type:input|name:market_value|placeholder:Unseite tržišnu vrijednost sportiste',
        'branch' => 'label:Grana|type:select|name:branch|options:Ritmička gimnastika,Sportska gimnastika|default:Izaberite granu gimnastike',
        'belt' => 'label:Pojas|type:select|name:belt|options:9 Kju Bijeli,8 Kju Žuti,7 Kju Narandžasti,6 Kju Crveni,5 Kju Zeleni,4 Kju Plavi,3 Kju Ljubičasti,2 Kju Smeđi,1 Kju Crni|default:Izaberite pojas sportiste',
        'style' => 'label:Stil|type:select|name:style|options:Slobodni stil,Prsni stil,Leđni stil,Delfin/Leptir stil,Mješovito|default:Izaberite stil plivanja sportiste',
        'distance' => 'label:Dionica stila|type:input|name:distance|placeholder:Unesite dionicu stila plivača',
        'coach' => 'label:Trener|type:input|name:coach|placeholder:Unesite ime trenera sportiste',
        'best_rank' => 'label:Najbolji rank|type:input|name:best_rank|placeholder:Unesite najbolji rank sportiste',
    ];

    protected $playerCommonValidationRules = [
        'avatar' => 'image|dimensions:min_width=512,min_height=512,max_width=2048,max_height=2048',
        'firstname' => 'required|string|max:255|alpha',
        'lastname' => 'required|string|max:255|alpha',
        'continent' => 'required|integer|exists:regions,id',
        'country' => 'required|integer|exists:regions,id',
        'province' => 'integer|exists:regions,id',
        'region' => 'integer|exists:regions,id',
        'municipality' => 'integer|exists:regions,id',
        'city' => 'required|max:255|string',
        'weight' => 'nullable|numeric|between:0,300.0',
        'height' => 'nullable|numeric|between:0,300.0',
        'facebook' => 'nullable|max:255|string',
        'instagram' => 'nullable|max:255|string',
        'twitter' => 'nullable|max:255|string',
        'youtube' => 'nullable|max:255|string',
        'video' => 'nullable|max:255|string',
        'biography' => 'nullable|string',
        'requested_club' => 'nullable|integer|exists:clubs,id',
        'player_nature' => 'integer|exists:player_natures,id',
        // History
        'history' => 'array',
        'history.*' => 'array',
        'history.*.season' => 'required|max:255|string',
        'history.*.club' => 'required|max:255|string',
        // Nagrade
        'nagrada' => 'array',
        'nagrada.*' => 'array',
        'nagrada.*.vrsta' => 'required|max:255|string|in:Medalja,Trofej/Pehar,Priznanje,Plaketa',
        'nagrada.*.tip' => 'required|max:255|string|in:Zlato,Srebro,Bronza,Ostalo',
        'nagrada.*.nivo' => 'required|max:255|string|in:Internacionalni nivo,Regionalni nivo,Državni nivo,Entitetski nivo,Drugo',
        'nagrada.*.takmicenje' => 'required|max:255|string',
        'nagrada.*.osvajanja' => 'nullable|integer',
        // Slike
        'galerija' => 'array',
        'galerija.*' => 'required|image',
    ];

    protected $playerUniqueValidationRules = [
        'preferred_leg' => 'nullable|max:255|string',
        'preferred_arm' => 'nullable|max:255|string',
        'rank' => 'nullable|integer',
        'discipline' => 'nullable|max:255|string',
        'best_result' => 'nullable|numeric',
        'agent' => 'nullable|max:255|string|alpha',
        'position' => 'nullable|max:255|string',
        'competition' => 'nullable|max:255|string',
        'category' => 'nullable|max:255|string',
        'market_value' => 'nullable|integer',
        'branch' => 'nullable|max:255|string',
        'belt' => 'nullable|max:255|string',
        'style' => 'nullable|max:255|string',
        'distance' => 'nullable|integer',
        'coach' => 'nullable|max:255|string|alpha',
        'best_rank' => 'nullable|integer',
    ];

    public function __construct(SportRepository $sportRepository, PlayerRepository $playerRepository, RegionRepository $regionRepository, ClubRepository $clubRepository) {
        $this->sportRepository = $sportRepository;
        $this->playerRepository = $playerRepository;
        $this->regionRepository = $regionRepository;
        $this->clubRepository = $clubRepository;
        $this->playerCommonValidationRules['date_of_birth'] = 'nullable|date|before_or_equal:' . Carbon::now()->toDateString();
        $this->playerCommonValidationRules['nagrada.*.sezona'] = 'required|digits:4|integer|min:1800|max:'.date('Y');
    }

    public function approvedPlayersList() {
        $players = $this->playerRepository
            ->getAllApproved();

        return view('sportisti', compact('players'));
    }

    public function notApprovedPlayersList() {
        $players = $this->playerRepository
            ->getAllNotApproved();

        return view('usportisti', compact('players'));
    }

    public function displayAddPlayerCategories() {
        $sports = $this->sportRepository
            ->getAllActiveSports();

        return view('athlete.add', compact('sports'));
    }

    public function displayAddPlayer($sport_id) {
        $sport = $this->sportRepository
            ->getById($sport_id);

        // Provjera da li je sport dostupan za dodavanje
        if(!$sport->active){
            abort(404);
        }

        $columns = Schema::getColumnListing($sport->players_table);
        $to_delete = ['id', 'player_type_id', 'created_at', 'updated_at'];
        $columns = array_diff($columns, $to_delete);

        $inputs = [];
        foreach ($this->allAttributesInputs as $key => $attribute) {
            foreach ($columns as $column) {
                if($key == $column) {
                    $inputs[$key] = $attribute;
                }
            }
        }

        foreach ($inputs as $key => $input) {
            $inputs[$key] = explode('|', $input);
            foreach ($inputs[$key] as $key_in => $input_attribute) {
                $newArray = explode(':', $input_attribute);

                $inputs[$key][$newArray[0]] = $newArray[1];
                unset($inputs[$key][$key_in]);
            }
        }

        if($sport->name == 'Atletika') {
            unset($inputs['discipline']['skijanje_options']);
        }

        if($sport->name == 'Skijanje') {
            $inputs['discipline']['options'] = $inputs['discipline']['skijanje_options'];
            unset($inputs['discipline']['skijanje_options']);
        }

        if($sport->name == 'Biciklizam') {
            unset($inputs['category']['karate_options']);
        }

        if($sport->name == 'Karate') {
            $inputs['category']['options'] = $inputs['category']['karate_options'];
            unset($inputs['category']['karate_options']);
        }

        foreach ($inputs as $key => $input) {
            if(array_key_exists('options', $input)){
                $inputs[$key]['options'] = explode(',', $input['options']);
            }
        }

        $inputs = json_decode(json_encode($inputs));

        $playerNatures = $this->playerRepository
            ->getAllPlayerNatures();

        $regions = $this->regionRepository
            ->getAll();

        $clubs = $this->clubRepository
            ->getAllForSport($sport_id);

        return view('athlete.new', compact('sport', 'inputs', 'playerNatures', 'regions', 'clubs'));
    }

    public function createPlayer(Request $request, $sport_id) {
        $sport = $this->sportRepository
            ->getById($sport_id);

        // Provjera da li je sport dostupan za dodavanje
        if(!$sport->active){
            abort(404);
        }

        $columns = Schema::getColumnListing($sport->players_table);
        $to_delete = ['id', 'player_type_id', 'created_at', 'updated_at'];
        $columns = array_diff($columns, $to_delete);

        $uniqueValidationRules = [];
        foreach ($columns as $column) {
            $uniqueValidationRules[$column] = $this->playerUniqueValidationRules[$column];
        }

        $completeValidationRules = array_merge($this->playerCommonValidationRules, $uniqueValidationRules);

        $validator = Validator::make($request->all(), $completeValidationRules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $createPlayer = $this->playerRepository
                ->createPlayer($request, $sport, $columns);

            if($createPlayer) {
                flash()->overlay('Uspješno ste dodali sportistu.', 'Čestitamo');
                return redirect('/athletes/' . $createPlayer->id);
            }
        }
    }

    public function showPlayer($id) {
        $player = $this->playerRepository
            ->getByIdWithAllData($id);

        if($player) {
            $regions = collect();
            $currentRegion = $player->region;
            while ($currentRegion) {
                $regions->put(strtolower($currentRegion->region_type->type), $currentRegion->name);

                $currentRegion = $currentRegion->parent_region;
            }

            $player->setAttribute('regions', $regions);

            return view('athlete.profile', compact('player'));
        }

        abort(404);
    }

    public function displayEditPlayer($id) {

        $player = $this->playerRepository
            ->getByIdWithAllData($id);

        if(!$player) {
            abort(404);
        }

        // Provjera da li je user napravio igraca
        $isOwner = $player->user->id == Auth::user()->id;
        if(!$isOwner) {
            abort(404);
        }

        $columns = Schema::getColumnListing($player->player_type->players_table);
        $to_delete = ['id', 'player_type_id', 'created_at', 'updated_at'];
        $columns = array_diff($columns, $to_delete);

        $inputs = [];
        foreach ($this->allAttributesInputs as $key => $attribute) {
            foreach ($columns as $column) {
                if($key == $column) {
                    $inputs[$key] = $attribute;
                }
            }
        }

        foreach ($inputs as $key => $input) {
            $inputs[$key] = explode('|', $input);
            foreach ($inputs[$key] as $key_in => $input_attribute) {
                $newArray = explode(':', $input_attribute);

                $inputs[$key][$newArray[0]] = $newArray[1];
                unset($inputs[$key][$key_in]);
            }
        }

        if($player->player_type->name == 'Atletika') {
            unset($inputs['discipline']['skijanje_options']);
        }

        if($player->player_type->name == 'Skijanje') {
            $inputs['discipline']['options'] = $inputs['discipline']['skijanje_options'];
            unset($inputs['discipline']['skijanje_options']);
        }

        if($player->player_type->name == 'Biciklizam') {
            unset($inputs['category']['karate_options']);
        }

        if($player->player_type->name == 'Karate') {
            $inputs['category']['options'] = $inputs['category']['karate_options'];
            unset($inputs['category']['karate_options']);
        }

        foreach ($inputs as $key => $input) {
            if(array_key_exists('options', $input)){
                $inputs[$key]['options'] = explode(',', $input['options']);
            }
        }

        $inputs = json_decode(json_encode($inputs));

        $playerNatures = $this->playerRepository
            ->getAllPlayerNatures();

        $regions = $this->regionRepository
            ->getAll();

        $clubs = $this->clubRepository
            ->getAllForSport($player->player_type->id);

        $clubRegions = collect();
        $currentRegion = $player->region;
        while ($currentRegion) {
            $clubRegions->put(strtolower($currentRegion->region_type->type), $currentRegion->id);

            $currentRegion = $currentRegion->parent_region;
        }

        $player->setAttribute('regions', $clubRegions);
        return view('athlete.edit', compact('player', 'inputs', 'playerNatures', 'regions', 'clubs'));
    }

    public function editPlayerGeneral($id, Request $request) {
        $player = $this->playerRepository
            ->getById($id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Sportista ne postoji.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'avatar' => 'image|dimensions:min_width=512,min_height=512,max_width=2048,max_height=2048',
            'firstname' => 'required|string|max:255|alpha',
            'lastname' => 'required|string|max:255|alpha',
            'continent' => 'required|integer|exists:regions,id',
            'country' => 'required|integer|exists:regions,id',
            'province' => 'integer|exists:regions,id',
            'region' => 'integer|exists:regions,id',
            'municipality' => 'integer|exists:regions,id',
            'city' => 'required|max:255|string',
            'facebook' => 'nullable|max:255|string',
            'instagram' => 'nullable|max:255|string',
            'twitter' => 'nullable|max:255|string',
            'youtube' => 'nullable|max:255|string',
            'video' => 'nullable|max:255|string',
            'player_nature' => 'integer|exists:player_natures,id'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        } else {

            $updatePlayerGeneral = $this->playerRepository
                ->updateGeneral($request, $player);

            if($updatePlayerGeneral) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uspješno ste izmjenili sekciju "Općenito" sportiste.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Izmjena sekcije "Općenito" sportiste neuspješna.'
            ]);
        }

    }

    public function editPlayerStatus($id, Request $request) {
        $player = $this->playerRepository
            ->getByIdWithAllData($id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Sportista ne postoji.'
            ]);
        }

        $allValidators = [
            'date_of_birth' => 'nullable|date|before_or_equal:' . Carbon::now()->toDateString(),
            'weight' => 'nullable|numeric|between:0,300.0',
            'height' => 'nullable|numeric|between:0,300.0',
            'requested_club' => 'nullable|integer|exists:clubs,id',
            'history' => 'array',
            'history.*' => 'array',
            'history.*.season' => 'required|max:255|string',
            'history.*.club' => 'required|max:255|string',
        ];

        foreach ($player->player_data as $key => $column) {
            $allValidators[$key] = $this->playerUniqueValidationRules[$key];
        }

        $validator = Validator::make($request->all(), $allValidators);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        } else {

            $updatePlayerStatus = $this->playerRepository
                ->updateStatus($request, $player);

            if($updatePlayerStatus) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uspješno ste izmjenili predispozicije sportiste.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Izmjena predispozicija sportiste neuspješna.'
            ]);
        }
    }

    public function editPlayerBiography($id, Request $request) {
        $player = $this->playerRepository
            ->getById($id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Sportista ne postoji.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'biography' => 'nullable|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        } else {

            $updatePlayerBiography = $this->playerRepository
                ->updateBiography($request, $player);

            if($updatePlayerBiography) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uspješno ste izmjenili biografiju sportiste.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Izmjena biografije sportiste neuspješna.'
            ]);
        }
    }

    public function editPlayerTrophies($id, Request $request) {
        $player = $this->playerRepository
            ->getById($id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Sportista ne postoji.'
            ]);
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
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        } else {

            $updatePlayerTrophies = $this->playerRepository
                ->updateTrophies($request, $player);

            if($updatePlayerTrophies) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uspješno ste izmjenili trofeje/nagrade sportiste.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Izmjena trofeja/nagrada sportiste neuspješna.'
            ]);
        }
    }

    public function editPlayerGallery($id, Request $request) {
        $player = $this->playerRepository
            ->getById($id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Sportista ne postoji.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'galerija' => 'array',
            'galerija.*' => 'required|image'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        } else {

            $updatePlayerGallery = $this->playerRepository
                ->updateGallery($request, $player);

            if($updatePlayerGallery) {
                return response()->json([
                    'success' => true,
                    'message' => 'Uspješno ste izmjenili galeriju sportiste.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Izmjena galerije sportiste neuspješna.'
            ]);
        }
    }

    public function searchPlayers(Request $request) {
        $playerNatures = $this->playerRepository
            ->getAllPlayerNatures();

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

        $query = Player::query();
        if(Input::filled('nature')){
            $query->where('player_nature', Input::get('nature'));
        }

        if(Input::filled('sport')){
            $query->whereHas('club', function ($query) {
                $query->where('clubs.sport_id', Input::get('sport'));
            });
        }

        if(!empty($region_ids)){
            $query->whereIn('region_id', $region_ids);
        }

        if(Input::filled('sort')){
            $sort = Input::get('sort');
            if($sort === 'name_desc') {
                $query->orderBy('firstname', 'DESC')->orderBy('lastname', 'DESC');
            } else if($sort === 'name_asc') {
                $query->orderBy('firstname', 'ASC')->orderBy('lastname', 'ASC');
            }
        }

        $results = $query
            ->paginate(16);

        return view('athlete.index', compact('playerNatures', 'sports', 'regions', 'results'));
    }

    public function getPlayerById($id) {
        $player = $this->playerRepository
            ->getByIdWithAllData($id);

        if(!$player) {
            return response()->json([
                'success' => false,
            ]);
        }

        $regions = collect();
        $currentRegion = $player->region;
        while ($currentRegion) {
            $regions->put(strtolower($currentRegion->region_type->type), $currentRegion->name);

            $currentRegion = $currentRegion->parent_region;
        }

        $player->setAttribute('regions', $regions);

        return response()->json([
            'success' => true,
            'player' => $player
        ]);
    }

    public function approvePlayer(Request $request) {
        $player = $this->playerRepository
            ->getById($request->id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Igrač ne postoji.'
            ]);
        }

        $player->status = 'active';

        if(!$player->save()) {
            return response()->json([
                'success' => false,
                'message' => 'Problem tokom odobravanja igrača.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Igrač odobren uspješno.'
        ]);
    }

    public function deletePlayer(Request $request) {
        $player = $this->playerRepository
            ->getById($request->id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Igrač ne postoji.'
            ]);
        }

        $player->status = 'deleted';

        if(!$player->save()) {
            return response()->json([
                'success' => false,
                'message' => 'Problem tokom brisanja igrača.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Igrač izbrisan uspješno.'
        ]);
    }

    public function refusePlayer(Request $request) {
        $player = $this->playerRepository
            ->getById($request->id);

        if(!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Igrač ne postoji.'
            ]);
        }

        $player->status = 'refused';

        if(!$player->save()) {
            return response()->json([
                'success' => false,
                'message' => 'Problem tokom odbijanja igrača.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Igrač odbijen uspješno.'
        ]);
    }

    public function getPlayerEditForm($id) {

        $player = $this->playerRepository
            ->getByIdWithAllData($id);

        if(!$player) {
            return null;
        }

        // Provjera da li je user napravio igraca
        $isOwner = $player->user->id == Auth::user()->id;
        if(!$isOwner) {
            return null;
        }

        $columns = Schema::getColumnListing($player->player_type->players_table);
        $to_delete = ['id', 'player_type_id', 'created_at', 'updated_at'];
        $columns = array_diff($columns, $to_delete);

        $inputs = [];
        foreach ($this->allAttributesInputs as $key => $attribute) {
            foreach ($columns as $column) {
                if($key == $column) {
                    $inputs[$key] = $attribute;
                }
            }
        }

        foreach ($inputs as $key => $input) {
            $inputs[$key] = explode('|', $input);
            foreach ($inputs[$key] as $key_in => $input_attribute) {
                $newArray = explode(':', $input_attribute);

                $inputs[$key][$newArray[0]] = $newArray[1];
                unset($inputs[$key][$key_in]);
            }
        }

        if($player->player_type->name == 'Atletika') {
            unset($inputs['discipline']['skijanje_options']);
        }

        if($player->player_type->name == 'Skijanje') {
            $inputs['discipline']['options'] = $inputs['discipline']['skijanje_options'];
            unset($inputs['discipline']['skijanje_options']);
        }

        if($player->player_type->name == 'Biciklizam') {
            unset($inputs['category']['karate_options']);
        }

        if($player->player_type->name == 'Karate') {
            $inputs['category']['options'] = $inputs['category']['karate_options'];
            unset($inputs['category']['karate_options']);
        }

        foreach ($inputs as $key => $input) {
            if(array_key_exists('options', $input)){
                $inputs[$key]['options'] = explode(',', $input['options']);
            }
        }

        $inputs = json_decode(json_encode($inputs));

        $playerNatures = $this->playerRepository
            ->getAllPlayerNatures();

        $regions = $this->regionRepository
            ->getAll();

        $clubs = $this->clubRepository
            ->getAllForSport($player->player_type->id);

        $clubRegions = collect();
        $currentRegion = $player->region;
        while ($currentRegion) {
            $clubRegions->put(strtolower($currentRegion->region_type->type), $currentRegion->id);

            $currentRegion = $currentRegion->parent_region;
        }

        $player->setAttribute('regions', $clubRegions);
        return view('partials.edit-player-form', compact('player', 'inputs', 'playerNatures', 'regions', 'clubs'));
    }
}
