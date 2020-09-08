<?php

namespace App\Repositories;

class BaseRepository
{
    public function retrieveSaveMessageForCreate($saveStatus)
    {
        $message = $saveStatus ? 'Added Successfully' : 'Error Occured! Try Again!';

        return $message;
    }

    public function retrieveSaveMessageForUpdate($saveStatus)
    {
        $message = $saveStatus ? 'Updated Successfully' : 'Error Occured! Try Again!';

        return $message;
    }

}

