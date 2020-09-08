<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class SystemHolidayMastModel extends Model
{
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'system_holiday_mast';
    protected $primaryKey = 'holiday_id';

    protected $searchable = [
        'columns' => [
            'holiday_descri' => 1
        ]
    ];

    const holidayForAllGenders = null;
    const holidayForMaleGender = 1;
    const holidayForFemaleGender = 2;
    const holidayForOthersGender = 3;

    public function fiscal()
    {
        return $this->belongsTo('App\FiscalYearModel', 'fy_year');
    }

	public function user()
	{
		return $this->hasOne('App\User', 'id', 'autho_id');
	}

    public function branch()
    {
        return $this->belongsToMany(SystemOfficeMastModel::class, 'branch_system_holidays','system_holiday_id','branch_id');
    }

    public function castes()
    {
        return $this->belongsToMany(Caste::class, 'caste_system_holiday','system_holiday_id','caste_id');
    }

    public function religions()
    {
        return $this->belongsToMany(Religion::class, 'religion_system_holiday','system_holiday_id','religion_id');
    }

    public function getRelatedReligionsIds()
    {
        return $this->religions->pluck('id');
    }

    public function getRelatedCastesIds()
    {
        return $this->castes->pluck('id');
    }

    public function getRelatedReligionsName()
    {
        return $this->religions->pluck('religion_name');
    }

    public function getRelatedCastesName()
    {
        return $this->castes->pluck('caste_name');
    }
}
