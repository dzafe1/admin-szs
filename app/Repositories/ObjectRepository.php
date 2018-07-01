<?php

namespace App\Repositories;

use App\Gallery;
use App\Objects;
use App\ObjectTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ObjectRepository {
    protected $model;
    protected $objectTypeModel;

    protected $generalAttributes = [
        'number_of_fields' => [
            'icon' => 'field.svg',
            'label' => [
                'bs' => 'Broj terena (sala)'
            ]
        ],
        'number_of_pools' => [
            'icon' => 'swimming-pool2.svg',
            'label' => [
                'bs' => 'Broj bazena'
            ]
        ],
        'number_of_tracks' => [
            'icon' => 'lanes.svg',
            'label' => [
                'bs' => 'Broj staza'
            ]
        ],
        'number_of_balls' => [
            'icon' => 'bowling-ball.svg',
            'label' => [
                'bs' => 'Broj kugli'
            ]
        ],
        'number_of_shooting_places' => [
            'icon' => 'target1.svg',
            'label' => [
                'bs' => 'Broj strelišta'
            ]
        ],
        'type_of_grass' => [
            'icon' => 'grass.svg',
            'label' => [
                'bs' => 'Vrsta travnjaka'
            ]
        ],
        'elevation' => [
            'icon' => 'nadmorska.svg',
            'label' => [
                'bs' => 'Nadmorska visina (m)'
            ]
        ],
        'stadium_length' => [
            'icon' => 'duzina.svg',
            'label' => [
                'bs' => 'Dužina terena (m)'
            ]
        ],
        'stadium_width' => [
            'icon' => 'sirina.svg',
            'label' => [
                'bs' => 'Širina terena (m)'
            ]
        ],
        'number_of_ski_tracks' => [
            'icon' => 'river-trail.svg',
            'label' => [
                'bs' => 'Ukupno staza'
            ]
        ],
        'number_of_ski_lifts' => [
            'icon' => 'ski-lift.svg',
            'label' => [
                'bs' => 'Ukupno liftova'
            ]
        ],
        'water_effects' => [
            'icon' => 'water-effects.svg',
            'label' => [
                'bs' => 'Vodeni efekti'
            ]
        ],
        'type_of_field' => [
            'icon' => 'parquet.svg',
            'label' => [
                'bs' => 'Vrsta podloge'
            ]
        ],
        'area' => [
            'icon' => 'scale-screen.svg',
            'label' => [
                'bs' => 'Površina objekta (m2)'
            ]
        ],
        'water_area' => [
            'icon' => 'scale-screen.svg',
            'label' => [
                'bs' => 'Vodena površina (m2)'
            ]
        ],
        'capacity' => [
            'icon' => 'gledaoci.svg',
            'label' => [
                'bs' => 'Kapacitet korisnika'
            ]
        ],
        'pool_capacity' => [
            'icon' => 'swimmer.svg',
            'label' => [
                'bs' => 'Kapacitet kupača'
            ]
        ],
        'stadium_capacity' => [
            'icon' => 'gledaoci.svg',
            'label' => [
                'bs' => 'Kapacitet gledaoca'
            ]
        ],
    ];

    protected $additionalAttributes = [
        'wifi' => [
            'icon' => 'wifi.svg',
            'label' => [
                'bs' => 'Wi-Fi'
            ]
        ],
        'parking' => [
            'icon' => 'parking-sign.svg',
            'label' => [
                'bs' => 'Parking'
            ]
        ],
        'restaurant' => [
            'icon' => 'cutlery.svg',
            'label' => [
                'bs' => 'Restoran'
            ]
        ],
        'hotels' => [
            'icon' => 'bed.svg',
            'label' => [
                'bs' => 'Hoteli i prenoćišta'
            ]
        ],
        'cafe' => [
            'icon' => 'coffee-cup.svg',
            'label' => [
                'bs' => 'Kafeterija'
            ]
        ],
        'access_to_disabled' => [
            'icon' => 'accessibility-sign.svg',
            'label' => [
                'bs' => 'Pristup za osobe sa invaliditetom'
            ]
        ],
        'number_of_locker_rooms' => [
            'icon' => 'room-key.svg',
            'label' => [
                'bs' => 'Broj svlačionica'
            ]
        ],
        'rent_equipment' => [
            'icon' => 'ski-glasses.svg',
            'label' => [
                'bs' => 'Najam opreme'
            ]
        ],
    ];

    protected $szsAttributes = [
        'hot_water_showers' => [
            'icon' => '',
            'label' => [
                'bs' => 'Tuševi sa toplom vodom'
            ]
        ],
        'result_board' => [
            'icon' => '',
            'label' => [
                'bs' => 'Tabla za rezultate'
            ]
        ],
        'kids_playground' => [
            'icon' => '',
            'label' => [
                'bs' => 'Igraona u sklopu objekta'
            ]
        ],
        'wardrobe_with_key' => [
            'icon' => '',
            'label' => [
                'bs' => 'Ormarić za garderobu sa ključem'
            ]
        ],
        'props' => [
            'icon' => '',
            'label' => [
                'bs' => 'Rekviziti za rekreaciju i trening'
            ]
        ],
        'air_conditioning' => [
            'icon' => '',
            'label' => [
                'bs' => 'Klimatizacija'
            ]
        ],
        'protective_net' => [
            'icon' => '',
            'label' => [
                'bs' => 'Zaštitne mreže'
            ]
        ],
        'optimum_temperature' => [
            'icon' => '',
            'label' => [
                'bs' => 'Optimalna temperatura'
            ]
        ],
        'video_surveillance' => [
            'icon' => '',
            'label' => [
                'bs' => 'Video nadzor'
            ]
        ],
        'equipment_rent' => [
            'icon' => '',
            'label' => [
                'bs' => 'Najam opreme'
            ]
        ],
        'kid_pools' => [
            'icon' => '',
            'label' => [
                'bs' => 'Bazeni za djecu'
            ]
        ],
        'entering_a_props' => [
            'icon' => '',
            'label' => [
                'bs' => 'Unos rekvizita (lopte, jastuci...)'
            ]
        ],
        'urine_detector' => [
            'icon' => '',
            'label' => [
                'bs' => 'Detektor urina'
            ]
        ],
        'reservations' => [
            'icon' => '',
            'label' => [
                'bs' => 'Rezervacije termina'
            ]
        ],
        'child_equipment' => [
            'icon' => '',
            'label' => [
                'bs' => 'Pomagala za djecu'
            ]
        ],
        'special_shoes' => [
            'icon' => '',
            'label' => [
                'bs' => 'Specijalna obuća'
            ]
        ],
        'hygiene_equipment' => [
            'icon' => '',
            'label' => [
                'bs' => 'Higijenska oprema (čarape)'
            ]
        ],
        'member_card' => [
            'icon' => '',
            'label' => [
                'bs' => 'Članska kartica'
            ]
        ],
        'maintenance_service' => [
            'icon' => '',
            'label' => [
                'bs' => 'Služba za održavanje'
            ]
        ],
        'emergency_intervention' => [
            'icon' => '',
            'label' => [
                'bs' => 'Hitna interventna jedinica'
            ]
        ],
        'skiing_school' => [
            'icon' => '',
            'label' => [
                'bs' => 'Škola skijanja'
            ]
        ],
        'snowboarding_school' => [
            'icon' => '',
            'label' => [
                'bs' => 'Škola snowboarding-a'
            ]
        ],
        'skiing_equipment_shops' => [
            'icon' => '',
            'label' => [
                'bs' => 'Prodavnice skijaške opreme'
            ]
        ],
        'mobile_rescue_team' => [
            'icon' => '',
            'label' => [
                'bs' => 'Mobilna spasilačka ekipa'
            ]
        ],
        'night_skiing' => [
            'icon' => '',
            'label' => [
                'bs' => 'Noćno skijanje'
            ]
        ],
        'commenting_cabins' => [
            'icon' => '',
            'label' => [
                'bs' => 'Komentatorske kabine'
            ]
        ],
        'speaker_system' => [
            'icon' => '',
            'label' => [
                'bs' => 'Razglas'
            ]
        ],
        'fan_shop' => [
            'icon' => '',
            'label' => [
                'bs' => 'Fan shop'
            ]
        ],
        'use_own_equipment' => [
            'icon' => '',
            'label' => [
                'bs' => 'Unos vlastite opreme'
            ]
        ],
        'equipment_service' => [
            'icon' => '',
            'label' => [
                'bs' => 'Servis opreme'
            ]
        ],
        'shooting_school' => [
            'icon' => '',
            'label' => [
                'bs' => 'Škola streljaštva'
            ]
        ],
        'protective_equipment' => [
            'icon' => '',
            'label' => [
                'bs' => 'Zaštitna oprema'
            ]
        ],
    ];
    /**
     * ObjectRepository constructor.
     * @param Objects $model
     * @param ObjectTypes $objectTypes
     */
    public function __construct(Objects $model, ObjectTypes $objectTypes){
        $this->model = $model;
        $this->objectTypeModel = $objectTypes;
    }

    public function getAllObjectTypes() {
        return $this->objectTypeModel->all();
    }

    public function getById($id) {
        return $this->model
            ->with(['type','images','user','proof_images'])
            ->where('id', $id)
            ->first();
    }

    public function getObjectTypeById($id) {
        return $this->objectTypeModel
            ->where('id', $id)
            ->first();
    }

    public function getAllApproved() {
        return $this->model
            ->with(['user', 'type'])
            ->where('status', 'active')
            ->get();
    }

    public function getAllNotApproved() {
        return $this->model
            ->with(['user', 'type'])
            ->where('status', '!=', 'deleted')
            ->where('status', '!=', 'active')
            ->get();
    }

    public function createObject(Request $request, ObjectTypes $object_type, $unique_columns) {
        $newLogoName = 'default.png';

        if($request->file('image')){
            $logo = $request->file('image');
            $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
            $destinationPath = public_path('/images/object_avatars');
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

        $createGeneralObject = $this->model->create([
            'name' => $request->get('name'),
            'image' => $newLogoName,
            'city' => $request->get('city'),
            'established_in' => new Carbon($request->get('established_in')),
            'facebook' => $request->get('facebook'),
            'instagram' => $request->get('instagram'),
            'twitter' => $request->get('twitter'),
            'youtube' => $request->get('youtube'),
            'region_id' => $region_id,
            'object_type_id' => $object_type->id,
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'history' => $request->get('history'),
            'user_id' => Auth::user()->id
        ]);

        if($createGeneralObject) {
            $attributesToInsert = [];
            $attributesToInsert['id'] = $createGeneralObject->id;

            foreach ($unique_columns as $column) {
                $attributesToInsert[$column] = $request->get($column);
            }

            $attributesToInsert['object_type_id'] = $object_type->id;
            $attributesToInsert['created_at'] = new Carbon();
            $attributesToInsert['updated_at'] = new Carbon();

            $createObjectUnique = DB::table($object_type->object_table)->insert($attributesToInsert);

            if($createObjectUnique) {

                if($request->file('galerija')){
                    $galerije = $request->file('galerija');
                    foreach($galerije as $key => $slika){
                        $newgalName = $key . '-' .time() . '-' .  $createGeneralObject->id . '.' . $slika->getClientOriginalExtension();
                        $destPath = public_path('/images/galerija_objekti');
                        $slika->move($destPath, $newgalName);

                        Gallery::create([
                            'image' => $newgalName,
                            'object_id' => $createGeneralObject->id
                        ]);
                    }
                }

                // Ovdje handle za dodavanje staza cijena i ostalog
                if($object_type->type == 'Balon') {
                    if($request->filled('tereni')){
                        $tereni = $request->get('tereni');
                        foreach ($tereni as $teren) {
                            if($teren) {
                                DB::table('balon_fields')->insert([
                                    'name' => $teren['name'],
                                    'sports' => implode(",",$teren['sports']),
                                    'type_of_field' => $teren['type_of_field'],
                                    'capacity' => $teren['capacity'] ? $teren['capacity'] : null,
                                    'public_capacity' => $teren['public_capacity'] ? $teren['public_capacity'] : null,
                                    'length' => $teren['length'] ? $teren['length'] : null,
                                    'width' => $teren['width'] ? $teren['width'] : null,
                                    'balon_objects_id' => $createGeneralObject->id,
                                    'created_at' => new Carbon(),
                                    'updated_at' => new Carbon()
                                ]);
                            }
                        }
                    }
                    if($request->filled('cjenovnik')){
                        $cjenovnik = $request->get('cjenovnik');
                        foreach ($cjenovnik as $cijena) {
                            if($cijena) {
                                DB::table('balon_prices')->insert([
                                    'sport' => $cijena['sport'],
                                    'name' => $cijena['name'],
                                    'price_per_hour' => $cijena['price_per_hour'],
                                    'balon_objects_id' => $createGeneralObject->id,
                                    'created_at' => new Carbon(),
                                    'updated_at' => new Carbon()
                                ]);
                            }
                        }
                    }
                } else if($object_type->type == 'Skijalište') {
                    if($request->filled('staze')){
                        $staze = $request->get('staze');
                        foreach ($staze as $staza) {
                            if($staza) {
                                DB::table('skiing_tracks')->insert([
                                    'name' => $staza['name'],
                                    'level' => $staza['level'],
                                    'length' => $staza['length'] ? $staza['length'] : null,
                                    'time' => $staza['time'] ? $staza['time'] : null,
                                    'start_point' => $staza['start_point'] ? $staza['start_point'] : null,
                                    'end_point' => $staza['end_point'] ? $staza['end_point'] : null,
                                    'skiing_objects_id' => $createGeneralObject->id,
                                    'created_at' => new Carbon(),
                                    'updated_at' => new Carbon()
                                ]);
                            }
                        }
                    }

                    if($request->filled('cjenovnik')){
                        $cjenovnik = $request->get('cjenovnik');
                        foreach ($cjenovnik as $cijena) {
                            if($cijena) {
                                DB::table('skiing_prices')->insert([
                                    'description' => $cijena['description'],
                                    'price' => $cijena['price'],
                                    'price_kids' => $cijena['price_kids'],
                                    'skiing_objects_id' => $createGeneralObject->id,
                                    'created_at' => new Carbon(),
                                    'updated_at' => new Carbon()
                                ]);
                            }
                        }
                    }
                }

                return $createGeneralObject;
            } else {
                $createGeneralObject->delete();
                return null;
            }
        }

        return null;
    }

    public function getByIdWithAllData($id) {
        $object = $this->getById($id);

        if($object) {
            $objectData = DB::table($object->type->object_table)
                ->where('id', $id)
                ->first();

            if($objectData) {
                $objectData = $this->unsetData(['id', 'object_id', 'created_at', 'updated_at'], $objectData);

                $objectDataGeneral = [];
                $objectDataAdditional = [];
                $objectDataSZS = [];

                foreach ($objectData as $key => $data) {
                    if(array_key_exists($key, $this->generalAttributes)) {
                        $objectDataGeneral[$key] = $data;
                    } else if(array_key_exists($key, $this->additionalAttributes)) {
                        $objectDataAdditional[$key] = $data;
                    } else if(array_key_exists($key, $this->szsAttributes)) {
                        $objectDataSZS[$key] = $data;
                    }
                }

                $dataGeneral = [
                    'data' => $objectDataGeneral,
                    'names' => $this->generalAttributes
                ];

                $dataAdditional = [
                    'data' => $objectDataAdditional,
                    'names' => $this->additionalAttributes
                ];

                $dataSZS = [
                    'data' => $objectDataSZS,
                    'names' => $this->szsAttributes
                ];

                $object->setAttribute('object_data_general', $dataGeneral);
                $object->setAttribute('object_data_additional', $dataAdditional);
                $object->setAttribute('object_data_szs', $dataSZS);


                if($object->type->type == 'Balon') {
                    $fields = DB::table('balon_fields')->where('balon_objects_id', $object->id)->get();
                    $object->setAttribute('fields', $fields);

                    $prices = DB::table('balon_prices')->where('balon_objects_id', $object->id)->get();
                    $object->setAttribute('prices', $prices);
                } else if($object->type->type == 'Skijalište') {
                    $tracks = DB::table('skiing_tracks')->where('skiing_objects_id', $object->id)->get();
                    $object->setAttribute('tracks', $tracks);

                    $prices = DB::table('skiing_prices')->where('skiing_objects_id', $object->id)->get();
                    $object->setAttribute('prices', $prices);
                }
            }

            return $object;
        }

        return null;
    }

    public function unsetData($dataToUnset = [], $array = []) {

        $dataToUnset = json_decode(json_encode($dataToUnset), true);
        $array = json_decode(json_encode($array), true);

        foreach ($dataToUnset as $data) {
            unset($array[$data]);
        }

        return $array;
    }

    public function updateGeneral(Request $request, Objects $object){
        $newLogoName = $object->image;

        if($request->file('image')){
            $logo = $request->file('image');
            $newLogoName = time() . '-' . Auth::user()->id . '.' . $logo->getClientOriginalExtension();
            $destinationPath = config('general.image_paths.object_images');
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

        $updateObjectGeneral = $object->update([
            'image' => $newLogoName,
            'name' => $request->get('name'),
            'region_id' => $region_id,
            'city' => $request->get('city'),
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'facebook' => $request->get('facebook'),
            'instagram' => $request->get('instagram'),
            'twitter' => $request->get('twitter'),
            'youtube' => $request->get('youtube')
        ]);

        if($updateObjectGeneral) {
            return $updateObjectGeneral;
        }

        return null;
    }

    public function updateStatus(Request $request, $object, $allUniqueAttributes) {

        $updateObjectCommonStatus = Objects::find($object->id)->update([
            'established_in' => Carbon::parse($request->get('established_in')),
        ]);

        if($updateObjectCommonStatus) {
            $uniqueColumns = [];

            $allUniqueAttributes['updated_at'] = Carbon::now();
            foreach ($allUniqueAttributes as $column) {
                if($request->has($column)) {
                    $uniqueColumns[$column] = $request->get($column);
                }
            }

            $updateObjectUniqueStatus = DB::table($object->type->object_table)
                ->where('id', $object->id)
                ->update($uniqueColumns);

            return true;
        }

        return null;

    }

    public function updateHistory(Request $request, Objects $object){

        $updateObjectHistory= $object->update([
            'history' => $request->get('history')
        ]);

        if($updateObjectHistory) {
            return $updateObjectHistory;
        }

        return null;
    }

    public function updateGallery(Request $request, Objects $object) {
        if($request->file('galerija')){
            $galerije = $request->file('galerija');
            foreach($galerije as $key => $slika){
                $newgalName = $key . '-' .time() . '-' .  $object->id . '.' . $slika->getClientOriginalExtension();
                $destPath = config('general.image_paths.object_gallery');
                $slika->move($destPath, $newgalName);

                Gallery::create([
                    'image' => $newgalName,
                    'object_id' => $object->id
                ]);
            }

            return true;
        }

        return null;
    }

    public function updateBalonFields(Request $request, Objects $object) {
        $oldIds = [];
        if($request->filled('tereni')){
            $tereni = $request->get('tereni');
            foreach ($tereni as $teren) {
                if($teren) {
                    if(!array_key_exists('id', $teren)) {
                        $new_field = DB::table('balon_fields')->insertGetId([
                            'name' => $teren['name'],
                            'sports' => implode(",",$teren['sports']),
                            'type_of_field' => $teren['type_of_field'],
                            'capacity' => $teren['capacity'] ? $teren['capacity'] : null,
                            'public_capacity' => $teren['public_capacity'] ? $teren['public_capacity'] : null,
                            'length' => $teren['length'] ? $teren['length'] : null,
                            'width' => $teren['width'] ? $teren['width'] : null,
                            'balon_objects_id' => $object->id,
                            'created_at' => new Carbon(),
                            'updated_at' => new Carbon()
                        ]);

                        $oldIds[] = $new_field;
                    } else {
                        $old_field = DB::table('balon_fields')->where('id', $teren['id'])->where('balon_objects_id', $object->id)->first();

                        if($old_field) {
                            $oldIds[] = $old_field->id;

                            DB::table('balon_fields')->where('id', $teren['id'])->where('balon_objects_id', $object->id)->update([
                                'name' => $teren['name'],
                                'sports' => implode(",",$teren['sports']),
                                'type_of_field' => $teren['type_of_field'],
                                'capacity' => $teren['capacity'] ? $teren['capacity'] : null,
                                'public_capacity' => $teren['public_capacity'] ? $teren['public_capacity'] : null,
                                'length' => $teren['length'] ? $teren['length'] : null,
                                'width' => $teren['width'] ? $teren['width'] : null,
                                'updated_at' => new Carbon()
                            ]);
                        }
                    }
                }
            }

            // Izbriši trofeje koje je user izbrisao
            DB::table('balon_fields')->where('balon_objects_id', $object->id)
                ->whereNotIn('id', $oldIds)
                ->delete();

            return true;
        } else {
            DB::table('balon_fields')->where('balon_objects_id', $object->id)
                ->delete();

            return true;
        }
    }

    public function updateBalonPrices(Request $request, Objects $object) {
        $oldIds = [];
        if($request->filled('cjenovnik')){
            $cjenovnik = $request->get('cjenovnik');
            foreach ($cjenovnik as $cijena) {
                if($cijena) {
                    if(!array_key_exists('id', $cijena)) {
                        $new_field = DB::table('balon_prices')->insertGetId([
                            'name' => $cijena['name'],
                            'sport' => $cijena['sport'],
                            'price_per_hour' => $cijena['price_per_hour'],
                            'balon_objects_id' => $object->id,
                            'created_at' => new Carbon(),
                            'updated_at' => new Carbon()
                        ]);

                        $oldIds[] = $new_field;
                    } else {
                        $old_field = DB::table('balon_prices')->where('id', $cijena['id'])->where('balon_objects_id', $object->id)->first();

                        if($old_field) {
                            $oldIds[] = $old_field->id;

                            DB::table('balon_prices')->where('id', $cijena['id'])->where('balon_objects_id', $object->id)->update([
                                'name' => $cijena['name'],
                                'sport' => $cijena['sport'],
                                'price_per_hour' => $cijena['price_per_hour'],
                                'updated_at' => new Carbon()
                            ]);
                        }
                    }
                }
            }

            // Izbriši trofeje koje je user izbrisao
            DB::table('balon_prices')->where('balon_objects_id', $object->id)
                ->whereNotIn('id', $oldIds)
                ->delete();

            return true;
        } else {
            DB::table('balon_prices')->where('balon_objects_id', $object->id)
                ->delete();

            return true;
        }
    }

    public function updateSkiTracks(Request $request, Objects $object) {
        $oldIds = [];
        if($request->filled('staze')){
            $staze = $request->get('staze');
            foreach ($staze as $staza) {
                if($staza) {
                    if(!array_key_exists('id', $staza)) {
                        $new_field = DB::table('skiing_tracks')->insertGetId([
                            'name' => $staza['name'],
                            'level' => $staza['level'],
                            'length' => $staza['length'] ? $staza['length'] : null,
                            'time' => $staza['time'] ? $staza['time'] : null,
                            'start_point' => $staza['start_point'] ? $staza['start_point'] : null,
                            'end_point' => $staza['end_point'] ? $staza['end_point'] : null,
                            'skiing_objects_id' => $object->id,
                            'created_at' => new Carbon(),
                            'updated_at' => new Carbon()
                        ]);

                        $oldIds[] = $new_field;
                    } else {
                        $old_field = DB::table('skiing_tracks')->where('id', $staza['id'])->where('skiing_objects_id', $object->id)->first();

                        if($old_field) {
                            $oldIds[] = $old_field->id;

                            DB::table('skiing_tracks')->where('id', $staza['id'])->where('skiing_objects_id', $object->id)->update([
                                'name' => $staza['name'],
                                'level' => $staza['level'],
                                'length' => $staza['length'] ? $staza['length'] : null,
                                'time' => $staza['time'] ? $staza['time'] : null,
                                'start_point' => $staza['start_point'] ? $staza['start_point'] : null,
                                'end_point' => $staza['end_point'] ? $staza['end_point'] : null,
                                'updated_at' => new Carbon()
                            ]);
                        }
                    }
                }
            }

            // Izbriši trofeje koje je user izbrisao
            DB::table('skiing_tracks')->where('skiing_objects_id', $object->id)
                ->whereNotIn('id', $oldIds)
                ->delete();

            return true;
        } else {
            DB::table('skiing_tracks')->where('skiing_objects_id', $object->id)
                ->delete();

            return true;
        }
    }

    public function updateSkiPrices(Request $request, Objects $object) {
        $oldIds = [];
        if($request->filled('cjenovnik')){
            $cjenovnik = $request->get('cjenovnik');
            foreach ($cjenovnik as $cijena) {
                if($cijena) {
                    if(!array_key_exists('id', $cijena)) {
                        $new_field = DB::table('skiing_prices')->insertGetId([
                            'description' => $cijena['description'],
                            'price' => $cijena['price'],
                            'price_kids' => $cijena['price_kids'],
                            'skiing_objects_id' => $object->id,
                            'created_at' => new Carbon(),
                            'updated_at' => new Carbon()
                        ]);

                        $oldIds[] = $new_field;
                    } else {
                        $old_field = DB::table('skiing_prices')->where('id', $cijena['id'])->where('skiing_objects_id', $object->id)->first();

                        if($old_field) {
                            $oldIds[] = $old_field->id;

                            DB::table('skiing_prices')->where('id', $cijena['id'])->where('skiing_objects_id', $object->id)->update([
                                'description' => $cijena['description'],
                                'price' => $cijena['price'],
                                'price_kids' => $cijena['price_kids'],
                                'updated_at' => new Carbon()
                            ]);
                        }
                    }
                }
            }

            // Izbriši trofeje koje je user izbrisao
            DB::table('skiing_prices')->where('skiing_objects_id', $object->id)
                ->whereNotIn('id', $oldIds)
                ->delete();

            return true;
        } else {
            DB::table('skiing_prices')->where('skiing_objects_id', $object->id)
                ->delete();

            return true;
        }
    }


}