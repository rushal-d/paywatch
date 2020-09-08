<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class FiscalYearAttendanceSum extends Model
{
    protected $fillable = ['fiscal_year', 'staff_central_id', 'branch_id'];
    use SearchableTrait;
    protected $searchable = [
        'columns' => [
            'staff_main_mast.name_eng' => 10,
        ],
        'joins' => [
            'staff_main_mast' => ['staff_main_mast.id', 'fiscal_year_attendance_sums.staff_central_id']
        ]
    ];

    public function staff()
    {
        return $this->belongsTo('\App\StafMainMastModel', 'staff_central_id');
    }

    public function fiscal()
    {
        return $this->belongsTo('\App\FiscalYearModel', 'fiscal_year');
    }

    public function branch()
    {
        return $this->belongsTo('\App\SystemOfficeMastModel', 'branch_id');
    }
}
