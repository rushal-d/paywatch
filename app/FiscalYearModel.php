<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class FiscalYearModel extends Model
{
    use SoftDeletes;
    use SearchableTrait;
    protected $table = 'fiscal_year';
    protected $searchable = [
        'columns' => [
            'fiscal_code' => 1
        ]
    ];

    //isActiveFiscalYear
    public function scopeIsActiveFiscalYear($query)
    {
        $query->where('fiscal_status', 1);
    }

    public function scopeAscOrder($query)
    {
        $query->orderBy('fiscal_start_date', 'ASC');
    }

    public function staffCitDeductions()
    {
        return $this->hasMany(StaffCitDeduction::class, 'fiscal_year_id');
    }
}
