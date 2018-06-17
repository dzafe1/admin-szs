<?php

namespace App\Repositories;

use App\Sport;

class SportRepository {
    protected $model;

    public function __construct(Sport $model)
    {
        $this->model = $model;
    }

    public function getAll() {
        return $this->model
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getAllActiveSports($with_disabilities = false) {

        return $this->model
            ->where('active', true)
            ->where('with_disabilities', $with_disabilities)
            ->orderBy('name', 'asc')
            ->get();

    }

    public function getById($id) {
        return $this->model
            ->where('id', $id)
            ->first();
    }
}