<?php

namespace App\Http\Controllers;

use App\DashainTiharSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashainTiharSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'Dashain Tihar Setup';
        $setup = DashainTiharSetup::first();
        if (empty($setup)) {
            $data['min_special_incentive_months'] = 0;
            $data['extra_facility_dashain_tihar_rate'] = 0;
            $data['incentive_amount'] = 0;
        } else {
            $data['min_special_incentive_months'] = $setup->min_special_incentive_months;
            $data['extra_facility_dashain_tihar_rate'] = $setup->extra_facility_dashain_tihar_rate;
            $data['incentive_amount'] = $setup->incentive_amount;
        }
        return view('dashain_tihar_setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setup = DashainTiharSetup::first();
        if (empty($setup)) {
            $setup = new DashainTiharSetup();
        }
        $setup->min_special_incentive_months = $request->min_special_incentive_months;
        $setup->extra_facility_dashain_tihar_rate = $request->extra_facility_dashain_tihar_rate;
        $setup->incentive_amount = $request->incentive_amount;
        $setup->updated_by = Auth::user()->id;
        $setup->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\DashainTiharSetup $dashainTiharSetup
     * @return \Illuminate\Http\Response
     */
    public function show(DashainTiharSetup $dashainTiharSetup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\DashainTiharSetup $dashainTiharSetup
     * @return \Illuminate\Http\Response
     */
    public function edit(DashainTiharSetup $dashainTiharSetup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\DashainTiharSetup $dashainTiharSetup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DashainTiharSetup $dashainTiharSetup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\DashainTiharSetup $dashainTiharSetup
     * @return \Illuminate\Http\Response
     */
    public function destroy(DashainTiharSetup $dashainTiharSetup)
    {
        //
    }
}
