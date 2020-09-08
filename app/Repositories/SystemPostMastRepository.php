<?php

namespace App\Repositories;

use App\SystemPostMastModel;

class SystemPostMastRepository
{
    public function getAllPosts()
    {
        $posts = SystemPostMastModel::get();
        return $posts;
    }

}
