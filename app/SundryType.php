<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class SundryType extends Model
{
    use SearchableTrait;
    use SoftDeletes;
    protected $searchable = [
        'columns' => [
            'title' => 1
        ]
    ];
	//check if cr or dr
    public function scopeIsCR($query, $type){
    	$is_cr = $query->where('id', $type)->value('type');
	    return ($is_cr == 1 ) ? true : false;
    }

	//check if cr or dr
	public function scopeIsDR($query, $type){
		$is_dr = $query->where('id', $type)->value('type');
		return ($is_dr == 2 ) ? true : false;
	}
}
