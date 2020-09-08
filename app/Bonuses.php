<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bonuses extends Model
{
    use SoftDeletes;
    public $table = 'bonuses';

    protected $fillable = [
        'received_amount'
    ];

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function fiscal_year()
    {
        return $this->belongsTo('\App\FiscalYearModel', 'fiscal_year_id');
    }

    public function branch()
    {
        return $this->belongsTo('\App\SystemOfficeMastModel', 'branch_id');
    }
}
