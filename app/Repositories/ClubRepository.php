<?php

namespace App\Repositories;

use App\Club;
use App\ClubCategory;

class ClubRepository {
    protected $model;
    protected $clubCategoryModel;

    public function __construct(Club $model, ClubCategory $clubCategory)
    {
        $this->model = $model;
        $this->clubCategoryModel = $clubCategory;
    }

    public function getSportCategories() {
        return $this->clubCategoryModel->all();
    }

    public function getAllForSport($sport_id) {
        return $this->model
            ->where('sport_id', $sport_id)
            ->get();
    }

    public function getAll() {
        return $this->model->all();
    }

    public function getAllApproved() {
        return $this->model
            ->with(['category', 'sport', 'creator'])
            ->where('status', 'active')
            ->get();
    }

    public function getAllNotApproved() {
        return $this->model
            ->with(['category', 'sport', 'creator'])
            ->where('status', '!=', 'deleted')
            ->where('status', '!=', 'active')
            ->get();
    }

}