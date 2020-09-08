<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Nicolaslopezj\Searchable\SearchableTrait;

class SundryTransaction extends Model
{
    use SearchableTrait;
    use SoftDeletes;
    protected $table = 'sundry_transactions';
    protected $primaryKey = 'id';

    protected $searchable = [
        'columns' => [
            'sundry_transactions.staff_central_id' => 10,
            'staff_main_mast.name_eng' => 10
        ],
        /*search by join table ->use table name*/
        'joins' => [
            'staff_main_mast' => ['sundry_transactions.staff_central_id', 'staff_main_mast.id'],
        ],

    ];

    protected static function boot()
    {
        parent::boot();

        if (!Session::get('role')) {
            static::addGlobalScope('staff_central_id', function (Builder $builder)  {
                $branch_id = Auth::user()->branch_id;
                $staff_ids = StafMainMastModel::where('branch_id', $branch_id)->pluck('id')->toArray();
                $builder->whereIn('staff_central_id', $staff_ids);
            });
        }

    }

    public function staff()
    {
        return $this->belongsTo('App\StafMainMastModel', 'staff_central_id');
    }

    public function sundryType()
    {
        return $this->belongsTo('\App\SundryType', 'transaction_type_id');
    }

}
