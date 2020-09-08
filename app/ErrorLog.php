<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = ['error_message', 'line_number', 'file_name'];
}
