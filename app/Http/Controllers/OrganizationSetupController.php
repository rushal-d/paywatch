<?php

namespace App\Http\Controllers;

use App\OrganizationSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class OrganizationSetupController extends Controller
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
        $data['title'] = 'Organization Setup';
        $data['organization_types'] = Config::get('constants.organization_type');
        $data['organization_structures'] = Config::get('constants.organization_structure');
        $data['overtime_calculation_types'] = Config::get('constants.overtime_calculation_types');
        $data['setup'] = OrganizationSetup::first();
        return view('organizationsetup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setup = OrganizationSetup::first();
        if (empty($setup)) {
            $setup = new OrganizationSetup();
            $setup->created_by = Auth::id();
        }
        $setup->organization_name = $request->organization_name;
        $setup->organization_address = $request->organization_address;
        $setup->organization_contact = $request->organization_contact;
        $setup->organization_website = $request->organization_website;
        $setup->organization_email = $request->organization_email;
        $setup->organization_code = $request->organization_code;
        $setup->organization_type = $request->organization_type;
        $setup->organization_structure = $request->organization_structure;
        $setup->max_overtime_hour = $request->max_overtime_hour;
        $setup->overtime_calculation_type = $request->overtime_calculation_type;
        if (isset($request->absent_weekend_on_cons_absent)) {
            $setup->absent_weekend_on_cons_absent = 1;
        } else {
            $setup->absent_weekend_on_cons_absent = 0;
        }
        if (isset($request->absent_publicholiday_on_cons_absent)) {
            $setup->absent_publicholiday_on_cons_absent = 1;
        } else {
            $setup->absent_publicholiday_on_cons_absent = 0;
        }
        $setup->updated_by = Auth::id();
        $setup->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\OrganizationSetup $organizationSetup
     * @return \Illuminate\Http\Response
     */
    public function show(OrganizationSetup $organizationSetup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\OrganizationSetup $organizationSetup
     * @return \Illuminate\Http\Response
     */
    public function edit(OrganizationSetup $organizationSetup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\OrganizationSetup $organizationSetup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrganizationSetup $organizationSetup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\OrganizationSetup $organizationSetup
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrganizationSetup $organizationSetup)
    {
        //
    }
}
