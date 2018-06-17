<?php

namespace App\Repositories;

use App\Association;

class AssociationRepository {
    protected $model;

    public function __construct(Association $model) {
        $this->model = $model;
    }

    public function getAll() {
        return $this->model->all();
    }

}