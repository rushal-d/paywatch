<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class PublicHoliday extends Model
{
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($holiday) {
            $holiday->religions()->detach();
            $holiday->castes()->detach();
        });
    }

    use SearchableTrait, SoftDeletes;
    protected $searchable = [
        'columns' => [
            'name' => 10,
            'description' => 5
        ]
    ];

    const holidayForAllGenders = null;
    const holidayForMaleGender = 1;
    const holidayForFemaleGender = 2;
    const holidayForOthersGender = 3;

    public function branch()
    {
        return $this->belongsTo(SystemOfficeMastModel::class, 'branch_id');
    }

    public function branches()
    {
        return $this->belongsToMany(SystemOfficeMastModel::class);
    }

    public function religions()
    {
        return $this->belongsToMany(Religion::class);
    }

    public function castes()
    {
        return $this->belongsToMany(Caste::class);
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
