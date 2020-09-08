<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankMastModel extends Model
{
    //
    use SoftDeletes;
    protected $table = 'bank_mast';
}
