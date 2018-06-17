<?php

namespace App\Repositories;

use App\Region;

class RegionRepository {
    protected $model;

    public function __construct(Region $model)
    {
        $this->model = $model;
    }

    public function getAll() {
        return $this->model
            ->with('region_type')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getCountries()
    {
        return $this->model
            ->whereHas('region_type', function ($query) {
                $query->where('type', '=', 'Country');
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getById($id){
        return $this->model
            ->where('id', $id)
            ->first();
    }
}