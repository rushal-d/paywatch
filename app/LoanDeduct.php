<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class LoanDeduct extends Model
{
    use SearchableTrait;
    use SoftDeletes;

    protected $searchable = [
        'columns' => [
            'loan_deducts.id' => 10,
//            'staff_main_mast.name_eng' => 10
        ],
        /*search by join table ->use table name*/
        /*'joins' => [
            'staff_main_mast' => ['loan_deducts.staff_central_id', 'staff_main_mast.id'],
        ],*/

    ];

    public const HOUSE_LOAN_TYPE_ID = 1;
    public const VEHICLE_LOAN_TYPE_ID = 2;


    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYearModel::class, 'fiscal_year_id');
    }

    //houseLoansType
    public function scopeHouseLoansType($query)
    {
        return $query->where('loan_type', LoanDeduct::HOUSE_LOAN_TYPE_ID);
    }

    //vehicleLoansType
    public function scopeVehicleLoansType($query)
    {
        return $query->where('loan_type', LoanDeduct::VEHICLE_LOAN_TYPE_ID);
    }

    public function houseLoan()
    {
        return $this->belongsTo(HouseLoanModelMast::class, 'loan_id');
    }


    public function vehicleLoan()
    {
        return $this->belongsTo(VehicalLoanModelTrans::class, 'loan_id');
    }

    public function staff()
    {
        return $this->belongsTo(StafMainMastModel::class, 'staff_central_id');
    }
}
