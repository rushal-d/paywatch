<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class SystemTdsMastModel extends Model
{
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'system_tdsdetails_mast';
    protected $primaryKey = 'id';

    const firstSlab = 1;

    protected $searchable = [
        'columns' => [
            'id' => 1
        ]
    ];

    public function fiscal()
    {
        return $this->belongsTo('App\FiscalYearModel', 'fy');
    }

    public function scopeGetSlab($query, $slab){
	    return $query->where('slab', $slab);
    }
}
