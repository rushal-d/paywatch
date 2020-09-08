<?php

namespace App\Repositories;

use App\Caste;

class CasteRepository
{
    private $model;

    public function __construct()
    {
        $this->model = Caste::query();
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
