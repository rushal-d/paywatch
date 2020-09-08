@extends('layouts.default', ['crumbroute' => 'payrollcreate'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">

    <style>
        #parent {
            height: 400px;
        }

        #fixTable {
            width: 1800px !important;
        }

        .table th, .table td {
            padding: 0rem;
            text-align: center;
        }

        th {
            font-size: 11px;
            padding: 0rem;
        }

        .staff_name {
            width: 160px;
        }

        .fixed {
            background-color: #00A97F !important;
            color: white;
        }


    </style>
@endsection
@section('content')

    <form method="POST" action="{{route('nepal-re-payroll-confirm')}}" id="confirm">
        {{ csrf_field() }}
        <input type="hidden" name="payroll_id" value="{{$payroll_details->id}}">
        <div class="row">
            <div class="col-md-12 col-sm-12">

                <div class="card">
                    <h5 class="card-header">Payroll Details</h5>
                    <div class="card-block">
                        <div class="payroll-details">
                            <div class="row">
                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong>Branch: </strong> {{ $payroll_details->branch->office_name }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong>Fiscal Year: </strong> {{ $payroll_details->fiscalyear->fiscal_code }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong> Salary
                                        Month: </strong> {{ \App\Helpers\BSDateHelper::_get_nepali_month($payroll_details->salary_month) }}
                                </div>

                                <div class="col-6 col-md-6 col-sm-6">
                                    <strong> Date From: </strong> {{  $payroll_details->from_date_np }}
                                    <strong> Date To: </strong> {{  $payroll_details->to_date_np }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong> Total Days: </strong> {{ $payroll_details->total_days }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <strong> Public Holidays: </strong> {{ $payroll_details->total_public_holidays }}
                                </div>

                                <div class="col-6 col-md-2 col-sm-6">
                                    <a href="{{route('attendance-detail-download',$payroll_details->id)}}"
                                       class="btn btn-sm btn-primary">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Action</h5>
                    <div class="card-block">


                            <div class="table-responsive" id="parent" style="overflow-y: hidden">
                                <table class="table table-bordered table-hover table-all" width="100%"
                                       cellspacing="0" id="fixTable">
                                    <tr>
                                        <td rowspan="2" class="small-cell">SN</td>
                                        <td rowspan="2" class="fullname-cell">Name Eng</td>
                                        <td rowspan="2">Designation</td>
                                        <td rowspan="2">Basic Salary</td>
                                        <td rowspan="2">Grade</td>
                                        <td rowspan="2">PF By Org</td>
                                        <td rowspan="2">Allowance</td>
                                        <td rowspan="2">Total Salary</td>
                                        <td rowspan="2">CIT</td>
                                        <td rowspan="2">PF Deduction</td>
                                        <td rowspan="2">Staff Loan</td>
                                        <td rowspan="2">Vehicle Installment</td>
                                        <td colspan="3">Tax Deduction</td>
                                        <td rowspan="2">Total Deduction</td>
                                        <td rowspan="2">Amount Sent to Bank</td>
                                        <td rowspan="2">Remarks</td>
                                    </tr>
                                    <tr>
                                        <td>1%</td>
                                        <td>Other</td>
                                        <td>36%</td>
                                    </tr>
                                    @foreach($payroll_informations as $payroll_information)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$payroll_information['staff_name']}}</td>
                                            <td>{{$payroll_information['designation']}}</td>
                                            <td>{{$payroll_information['basic_salary']}}</td>
                                            <td>{{$payroll_information['grade']}}</td>
                                            <td>{{$payroll_information['profund_by_organization']}}</td>
                                            <td>{{$payroll_information['allowance']}}</td>
                                            <td>{{$payroll_information['total_salary']}}</td>
                                            <td>{{$payroll_information['cit_deduction']}}</td>
                                            <td>{{$payroll_information['total_pf_deduction']}}</td>
                                            <td>{{$payroll_information['house_loan']}}</td>
                                            <td>{{$payroll_information['vehicle_loan']}}</td>
                                            <td>
                                                <input type="hidden" class="salary_before_tax"
                                                       value="{{$payroll_information['salary_before_tax'] ?? 0}}">
                                                <input type="hidden" class="total_deduction_before_tax"
                                                       value="{{$payroll_information['total_deduction_before_tax'] ?? 0}}">

                                                <input type="text" size="3"
                                                       value="{{$payroll_information['slab_one'] ?? 0}}"
                                                       class="trigger-total-deduct"
                                                       name="slab_one[{{$payroll_information['id']}}]">
                                            </td>
                                            <td>
                                                <input type="text" size="3"
                                                       value="{{$payroll_information['other'] ?? 0}}"
                                                       class="trigger-total-deduct"
                                                       name="slab_other[{{$payroll_information['id']}}]">
                                            </td>
                                            <td>
                                                <input type="text" size="3"
                                                       value="{{$payroll_information['slab_36'] ?? 0}}"
                                                       class="trigger-total-deduct"
                                                       name="slab_36[{{$payroll_information['id']}}]">
                                            </td>
                                            <td class="total_deduction">

                                                {{$payroll_information['total_deduction'] ?? 0}}
                                            </td>
                                            <td class="amount_sent_to_bank">
                                                {{$payroll_information['amount_sent_to_bank'] ?? 0}}
                                            </td>
                                            <td>
                                                <input type="text" value="" placeholder="Remarks" class="form-control"
                                                       name="remarks[{{$payroll_information['id']}}]">
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                            <div class="mt-5 float-right">
                                <button class="btn btn-success">
                                    Confirm
                                </button>
                            </div>

                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection


@section('script')
    <script src="{{ asset('assets/js/vex.combined.js') }}"></script>
    <script>
        //apply vex dialog
        (function () {
            vex.defaultOptions.className = 'vex-theme-os'
            //vex.dialog.buttons.YES.text = 'Yes'
            vex.dialog.buttons.YES.className = 'btn btn-danger'
        })();
    </script>
    <script>


        $('.trigger-total-deduct').keyup(delay(function () {
            let tr = $(this).parent().parent()
            let tax_fields = tr.find('.trigger-total-deduct');
            let salary_before_tax = parseFloat(tr.find('.salary_before_tax').val());
            let total_deduction_before_tax = parseFloat(tr.find('.total_deduction_before_tax').val());
            let total_tax_amount = 0;
            $.each(tax_fields, function (index, value) {
                total_tax_amount += parseFloat($(value).val());
            });
            let total_deduction = parseFloat(total_deduction_before_tax + total_tax_amount).toFixed(3);
            let amount_sent_to_bank = parseFloat(salary_before_tax - total_tax_amount).toFixed(3);
            tr.find('.total_deduction').text(total_deduction)
            tr.find('.amount_sent_to_bank').text(amount_sent_to_bank)

        }, 500));

        function delay(callback, ms) {
            var timer = 0;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }
    </script>
    <script src="{{asset('assets/tableHeadFixer/tableHeadFixer.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#fixTable").tableHeadFixer({"left": 3});
        });
    </script>
@endsection
