@extends('layouts.default', ['crumbroute' => 'nepal-re-tax-calculation'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        #parent {
            height: 1000px;
        }

        #fixTable {
            width: 1800px !important;
        }


    </style>
@endsection
@section('content')

    <div class="basic-info  card">
        <div class="card-header">
            Tax Calculation
        </div>
        <div class="card-block">
            <div class="card-text">
                <div class="table-scrollable dragscroll" id="parent" style="overflow-y: hidden">
                    <table class="table table-bordered table-hover table-all" width="100%"
                           cellspacing="0" id="fixTable">
                        <thead align="left">
                        <th class="fixed">SN</th>
                        <th class="fixed">Post</th>
                        <th class="fixed">Mr/MRS</th>
                        <th class="fixed">Name</th>
                        <th>Basic Salary Per Month</th>
                        <th>Basic Salary For The Year</th>
                        <th>Grade Before Increment</th>
                        <th>Grade After Increment</th>
                        @foreach($allowances as $allowance)
                            <th>{{$allowance->allow_title}}</th>
                        @endforeach
                        <th>DIFF Vehicle Int. Income</th>
                        <th>House Loan Income</th>
                        <th>PF By ORG</th>
                        <th>Overtime Allowance</th>
                        <th>Bonus</th>
                        <th>Gross Annual Income</th>
                        <th>Contributed CIT in the Year</th>
                        <th>One third</th>
                        <th>CIT Till Date</th>
                        <th>PF</th>
                        <th>Total Contribution</th>
                        <th>Allowable Contribution</th>
                        <th>Insurance Premium</th>
                        <th>Total Taxable Income</th>
                        <th>Single(S)/Couple(C)</th>
                        <th>Male(M)/Female(F)</th>
                        @foreach($tax_slabs as $slab)
                            <th>{{$slab->percent}}% (YEARLY)</th>
                        @endforeach
                        <th>TOTAL ESTIMATED TAX YEARLY</th>
                        <th>Female Employement Tax Credit</th>
                        <th>Total Annual Tax</th>
                        <th>Per Month Tax</th>
                        @foreach($tax_slabs as $slab)
                            <th>{{$slab->percent}}% (MOTHLY)</th>
                        @endforeach
                        </thead>
                        @foreach($tax_details as $tax_detail)
                            <tr>
                                <td class="fixed">{{$loop->iteration}}</td>
                                <td class="fixed">{{$tax_detail['post'] ?? ''}}</td>
                                <td class="fixed">{{$tax_detail['title'] ?? ''}}</td>
                                <td class="fixed">{{$tax_detail['name'] ?? ''}}</td>
                                <td>{{round($tax_detail['basic_salary_monthly'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['basic_salary'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['grade_before_increment'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['grade_after_increment'] ?? 0,2)}}</td>
                                @foreach($allowances as $allowance)
                                    <td>{{round($tax_detail['allowance_payment'][$allowance->allow_id]) ?? 0}}</td>
                                @endforeach
                                <td>{{round($tax_detail['vehicle_loan_diff_income'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['house_loan_diff_income'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['pf_by_organization'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['overtime_allowance'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['bonus'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['gross_annual_income'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['contribution_in_the_year'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['one_third'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['cit_till_date'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['total_pf'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['total_contribution'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['allowable_contribution'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['insurance_premium'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['total_taxable_income'] ??0,2)}}</td>
                                <td>{{$tax_detail['marital_status'] ?? ''}}</td>
                                <td>{{$tax_detail['gender'] ?? ''}}</td>
                                @foreach($tax_slabs as $slab)
                                    <td>{{round($tax_detail['yearly_tax_slab'][$slab->slab] ?? 0,2)}}</td>
                                @endforeach
                                <td>{{round($tax_detail['total_taxable_estimated_tax'] ?? 0,2)}}</td>
                                <td></td>
                                <td>{{round($tax_detail['total_taxable_estimated_tax'] ?? 0,2)}}</td>
                                <td>{{round($tax_detail['total_taxable_estimated_tax_monthly'] ?? 0,2)}}</td>
                                @foreach($tax_slabs as $slab)
                                    <td>{{round($tax_detail['monthly_tax_slab'][$slab->slab] ?? 0,2)}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
                <form action="{{route('nepal-re-tax-calculation-save')}}" method="POST">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{$_GET['branch_id']}}">
                    <input type="hidden" name="fiscal_year_id" value="{{$_GET['fiscal_year_id']}}">
                    <div class="form-group float-right mt-5">
                        <button class="btn btn-success">
                            Save/Update
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection


@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script src="{{asset('assets/tableHeadFixer/tableHeadFixer.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#fixTable").tableHeadFixer({"left": 4});
        });
    </script>
@endsection
