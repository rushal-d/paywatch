<?php

namespace App\Repositories;

use App\Religion;

class ReligionRepository
{
    private $model;

    public function __construct()
    {
        $this->model = Religion::query();
    }

    public function all()
    {
        return $this->model;
    }

    public function getAll()
    {
        return $this->model->get();
    }


}
