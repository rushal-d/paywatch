@extends('layouts.default', ['crumbroute' => 'payrollcreate'])
@section('title', $title)
@section('content')
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
    {{--{{ Form::open(array('route' => 'attendance-detail-save'))  }}--}}
    <form method="POST"
          @if(empty($payroll_details->confirmed_by)) action="{{ route('attendance-calculate') }}"
          @else action="{{ route('payroll-difference-calculate') }}" @endif>
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">Action</h5>
                    <div class="card-block">

                        <div class="table-responsive">
                            <table width="100%">
                                <tbody>
                                <tr>
                                    <td><b>Payroll Branch</b>: {{$payroll_details->branch->office_name ?? ''}}</td>
                                    <td><b>Month</b>: {{$payroll_details->salary_month ?? ''}}</td>
                                    <td><b>No. Of Staff</b>: {{$payroll_details->attendanceSummary->count() ?? ''}}</td>
                                    <td><b>Total Work
                                            Hour</b>: {{$payroll_details->attendanceSummary->sum('total_work_hour') ?? ''}}
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive" id="parent" style="overflow-y: hidden">
                            <table class="table table-bordered table-hover table-all" width="100%"
                                   cellspacing="0" id="fixTable">
                                <thead align="left">
                                <th class="fixed">Id</th>
                                <th class="staff_name fixed">Staff Name</th>
                                <th class="fixed"><p data-placement="bottom" data-toggle="tooltip"
                                                     title="Total Work Hour">WH</p></th>
                                <th class="fixed"><p data-placement="bottom" data-toggle="tooltip" title="Present Days">
                                        PD</p></th>
                                <th class="fixed"><p data-placement="bottom" data-toggle="tooltip" title="Absent Days">
                                        Abs.Days</p></th>
                                <th class="fixed"><p data-placement="bottom" data-toggle="tooltip"
                                                     title="Home Leave Balance">HL Bal.</p></th>
                                <th class="fixed"><p data-placement="bottom" data-toggle="tooltip"
                                                     title="Sick Leave Balance">SL Bal.</p></th>
                                <th class="fixed"><p data-placement="bottom" data-toggle="tooltip"
                                                     title="Substitute Leave Balance">SubL Bal.</p></th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Grant Home Leave">GHL </p>
                                    <input type="checkbox" class="use_home_leave"></th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Grant Sick Leave">GSL</p>
                                </th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Grant Substitute Leave">
                                        GSUBL</p> <input type="checkbox" class="use_substitute_leave"></th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Grant Maternity Leave">
                                        GML</p></th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Grant Maternity Care Leave">
                                        GMCL</p></th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Grant Funeral Leave">
                                        GFL </p></th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Redeem Home Leave"> RHL </p>
                                </th>
                                <th><p data-placement="bottom" data-toggle="tooltip" title="Redeem Sick Leave">RSL</p>
                                </th>
                                <th class="table-head-right text-right"><p data-placement="bottom"
                                                                           data-toggle="tooltip"
                                                                           title="Home Loan">H.Loan</p>
                                </th>
                                <th class="table-head-right text-right"><p data-placement="bottom"
                                                                           data-toggle="tooltip"
                                                                           title="Vehicle Loan">
                                        V.Loan</p></th>
                                <th class="table-head-right">Net Payment</th>
                                </thead>
                                <tbody>
                                <?php $count = 0; ?>
                                @foreach($attendance as $atten)
                                    <tr>
                                        <td class="fixed">
                                            <input type="hidden" class="staff-central-id"
                                                   value="{{$atten->staff_central_id}}">
                                            {{ $atten->staff->main_id ?? ''  }}</td>
                                        <td class="fixed">
                                            {{$atten->staff->name_eng}}
                                        </td>
                                        <td class="fixed">{{ $atten->total_work_hour}}</td>
                                        <td class="fixed"><p>{{ $atten->present_days}}</p>
                                            <input type="hidden" class="present-days"
                                                   value="{{ $atten->present_days}}">
                                        </td>
                                        <td class="fixed">
                                            <p class="absent_day_remaining">{{ $atten->absent_days}}</p>
                                            <input type="hidden" class="absent-days"
                                                   value="{{ $atten->absent_days}}">
                                        </td>
                                        @php
                                            $payrollConfirm=$payroll_details->payrollConfirm->where('staff_central_id',$atten->staff_central_id)->first();
                                                $home_leave_remaining= ($atten->staff->homeLeaveBalanceLast->balance ?? 0)+$atten->earned_home_leave -
                                                (!empty($payroll_details->confirmed_by) ? !empty($payrollConfirm)? ($payrollConfirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code',3)->first()->earned ?? 0):0:0);
                                                $sick_leave_remaining= ($atten->staff->sickLeaveBalanceLast->balance ?? 0)+$atten->earned_sick_leave-
                                                                                            (!empty($payroll_details->confirmed_by) ? !empty($payrollConfirm)? ($payrollConfirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code',4)->first()->earned ?? 0):0:0);
                                                $substitute_leave_remaining= ($atten->staff->substituteLeaveBalanceLast->balance ?? 0)+$atten->earned_substitute_leave-
                                                   (!empty($payroll_details->confirmed_by) ? !empty($payrollConfirm)? ($payrollConfirm->payrollConfirmLeaveInfos->where('leaveMast.leave_code',8)->first()->earned ?? 0):0:0);
                                                $maternity_leave_remaining=$atten->staff->maternityLeaveBalanceLast->balance ?? 0;
                                                $maternity_care_leave_remaining=$atten->staff->maternityCareLeaveBalanceLast->balance ?? 0;
                                                $funeral_leave_remaining=$atten->staff->funeralLeaveBalanceLast->balance ?? 0;
                                                $force_redemption_home = 0;
                                                $force_redemption_sick = 0;
                                        @endphp
                                        @if(!empty($home_leave_remaining))
                                            @if($home_leave_remaining>$total_home_leave_from_system)
                                                @php
                                                    $force_redemption_home = $home_leave_remaining - $total_home_leave_from_system;
                                                @endphp
                                            @endif
                                        @endif
                                        @if(!empty($sick_leave_remaining))
                                            @if($sick_leave_remaining > $total_sick_leave_from_system)
                                                @php
                                                    $force_redemption_sick = $sick_leave_remaining - $total_sick_leave_from_system;
                                                @endphp
                                            @endif
                                        @endif
                                        <td class="fixed">
                                            <p class="remaining_home_leave_balance">{{$home_leave_remaining-$force_redemption_home}}</p>
                                            <input type="hidden"
                                                   value="{{$home_leave_remaining}}"
                                                   class="home_balance">

                                        </td>
                                        <td class="fixed">
                                            <p class="remaining_sick_leave_balance"> {{round(($sick_leave_remaining - $force_redemption_sick),2)}}</p>

                                            <input type="hidden"
                                                   value={{$sick_leave_remaining}} class="sick_balance">
                                        </td>
                                        <td class="fixed">
                                            <p class="remaining_substitute_leave_balance"> {{$substitute_leave_remaining}}</p>
                                            <input type="hidden" class="substitute_balance"
                                                   value="{{$substitute_leave_remaining}}">

                                            <input type="hidden" class="maternity_balance"
                                                   value="{{$maternity_leave_remaining}}">

                                            <input type="hidden" class="maternity_care_balance"
                                                   value="{{$maternity_care_leave_remaining}}">

                                            <input type="hidden" class="funeral_balance"
                                                   value="{{$funeral_leave_remaining}}">
                                        </td>
                                        <td>
                                            <input type="text" size="2"
                                                   value="{{$payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->grant_home_leave ?? 0}}"
                                                   name="grant_home_leave[{{ $atten->staff_central_id }}]"
                                                   class="grant-leave grant-home-leave effect-payment">
                                        </td>
                                        <td>
                                            <input type="text" size="2"
                                                   value="{{$payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->grant_sick_leave ?? 0}}"
                                                   name="grant_sick_leave[{{ $atten->staff_central_id }}]"
                                                   class="grant-leave grant-sick-leave effect-payment">
                                        </td>
                                        <td>
                                            <input type="text" size="2"
                                                   value="{{$payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->grant_substitute_leave ?? 0}}"
                                                   name="grant_substitute_leave[{{ $atten->staff_central_id }}]"
                                                   class="grant-leave grant-substitute-leave effect-payment">
                                        </td>

                                        <td>
                                            <input type="text" size="2"
                                                   value="{{$payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->grant_maternity_leave ?? 0}}"
                                                   name="grant_maternity_leave[{{ $atten->staff_central_id }}]"
                                                   class="grant-leave grant-maternity-leave effect-payment">
                                        </td>
                                        <td>
                                            <input type="text" size="2"
                                                   value="{{$payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->grant_maternity_care_leave ?? 0}}"
                                                   name="grant_maternity_care_leave[{{ $atten->staff_central_id }}]"
                                                   class="grant-leave grant-maternity-care-leave effect-payment">
                                        </td>
                                        <td>
                                            <input type="text" size="2"
                                                   value="{{$payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->grant_funeral_leave ?? 0}}"
                                                   name="grant_funeral_leave[{{ $atten->staff_central_id }}]"
                                                   class="grant-leave grant-funeral-leave effect-payment">
                                        </td>
                                        <td>
                                            <input type="hidden" value="{{$force_redemption_home}}"
                                                   class="force_redemption_home">
                                            <input type="text"
                                                   name="redeem_home_leave[{{ $atten->staff_central_id }}]"
                                                   class="redeem_home_leave effect-payment" size="2"
                                                   value="{{ $payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->redeem_home_leave ?? $force_redemption_home}}">
                                        </td>
                                        <td>

                                            <input type="hidden" value="{{$force_redemption_sick}}"
                                                   class="force_redemption_sick">

                                            <input type="text"
                                                   name="redeem_sick_leave[{{ $atten->staff_central_id }}]"
                                                   class="redeem_sick_leave effect-payment" size="2"
                                                   value="{{  $payroll_calculation_data->where('staff_central_id',$atten->staff_central_id)->first()->redeem_sick_leave ??  $force_redemption_sick}}">
                                        </td>
                                        <td align="left">
                                            <input type="hidden"
                                                   name="home_loan_installment_amount[{{$atten->staff_central_id }}]"
                                                   class="loan-installment home-loan-installment effect-payment"
                                                   value="{{$atten->staff->loanDeducation->where('loan_type',1)->first()->loan_deduct_amount?? null}}"
                                                   size="5">

                                            {{$atten->staff->loanDeducation->where('loan_type',1)->first()->loan_deduct_amount ?? 0}}

                                        </td>
                                        <td align="left">
                                            <input type="hidden"
                                                   name="vehicle_loan_installment_amount[{{$atten->staff_central_id }}]"
                                                   class="loan-installment vehicle-loan-installment effect-payment"
                                                   value="{{$atten->staff->loanDeducation->where('loan_type',2)->first()->loan_deduct_amount ?? null}}"
                                                   size="5">
                                            {{$atten->staff->loanDeducation->where('loan_type',2)->first()->loan_deduct_amount ?? 0}}

                                        </td>

                                        <td align="right">
                                            <p class="netpayment">
                                                {{$atten->net_payment}}
                                            </p>

                                        </td>
                                        <input type="hidden" name="staff_central_id[]"
                                               value="{{ $atten->staff_central_id }}">
                                    </tr>
                                    <?php $count++; ?>
                                @endforeach

                                </tbody>

                            </table>
                        </div>
                        <input type="hidden" name="payroll_id" id="payroll_id" value="{{$payroll_id}}">
                    </div>
                </div>
                {{--  Save --}}
                <div class="row">
                    <div class="col-md-12">
                        @if(empty($payroll_details) || empty($payroll_details->confirmed_by))
                            <div class="text-right form-control">
                                {{ Form::submit('Next',array('class'=>'btn btn-success btn-lg'))}}
                            </div>
                        @else
                            <h6>The payroll for this month already been confirmed</h6>

                            <div class="text-right form-control">
                                {{ Form::submit('Next',array('class'=>'btn btn-success btn-lg'))}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection


@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script>
        $('.redeem_home_leave').keyup(delay(function () {
            let $this = $(this);
            let redeem_leave = parseFloat($this.val());
            let parent = $this.parent().parent()
            let leave_balance = parseFloat(parent.find('.home_balance').val());
            let granted_leave = parseFloat(parent.find('.grant-home-leave').val());
            let consumed_leave = granted_leave + redeem_leave;
            if (consumed_leave > leave_balance) {
                redeem_leave = parseFloat(leave_balance - granted_leave).toFixed(2);
                $this.val(redeem_leave);
                consumed_leave = parseFloat(redeem_leave) + parseFloat(granted_leave);
            }
            let remaining_leave = leave_balance - consumed_leave;
            parent.find('.remaining_home_leave_balance').text(remaining_leave.toFixed(2));

        }, 500));
        $('.redeem_sick_leave').keyup(delay(function () {
            let $this = $(this);
            let redeem_leave = parseFloat($this.val());
            let parent = $this.parent().parent()
            let leave_balance = parseFloat(parent.find('.sick_balance').val());
            let granted_leave = parseFloat(parent.find('.redeem_sick_leave').val());
            let consumed_leave = granted_leave + redeem_leave;
            if (consumed_leave > leave_balance) {
                redeem_leave = Math.floor(parseFloat(leave_balance - granted_leave));
                $this.val(redeem_leave);
                consumed_leave = parseFloat(redeem_leave) + parseFloat(granted_leave);
            }
            let remaining_leave = leave_balance - consumed_leave;
            parent.find('.remaining_sick_leave_balance').text(remaining_leave.toFixed(2));
        }, 500));


        $('.grant-sick-leave').keyup(delay(function () {
            let $this = $(this);
            let granted_leave = parseFloat($this.val());
            let parent = $this.parent().parent();
            let absent_days = parseInt(parent.find('.absent-days').val());

            let grant_leaves = parent.find('.grant-leave');
            let total_granted_leaves = 0;
            $.each(grant_leaves, function (index, value) {
                total_granted_leaves += parseInt($(value).val());
            });
            if (parseInt(total_granted_leaves) > parseInt(absent_days)) {
                let excess_days = parseInt(total_granted_leaves) - parseInt(absent_days);
                granted_leave -= excess_days;
                total_granted_leaves -= excess_days;
                $this.val(granted_leave);
            }

            parent.find('.absent_day_remaining').text(absent_days - total_granted_leaves);


            let leave_balance = parseFloat(parent.find('.sick_balance').val());
            let redeem_leave = parseFloat(parent.find('.redeem_sick_leave').val());
            let consumed_leave = parseFloat(granted_leave + redeem_leave);
            /* if (consumed_leave > leave_balance) {
                 granted_leave = Math.floor(parseFloat(leave_balance - redeem_leave).toFixed(2));
                 $this.val(granted_leave);
                 consumed_leave = parseFloat(redeem_leave) + parseFloat(granted_leave);
             }*/
            let remaining_leave = leave_balance - consumed_leave;
            parent.find('.remaining_sick_leave_balance').text(remaining_leave.toFixed(2));

        }, 500));

        $('.grant-substitute-leave').keyup(delay(function () {

            let $this = $(this);
            let parent = $this.parent().parent();
            let leave_balance = parseFloat(parent.find('.substitute_balance').val());

            computeLeave($this, leave_balance, parent.find('.remaining_substitute_leave_balance'))
        }, 500));

        $('.grant-maternity-leave').keyup(delay(function () {
            let $this = $(this);
            let parent = $this.parent().parent();
            let leave_balance = parseFloat(parent.find('.maternity_balance').val());
            computeLeave($this, leave_balance)
        }, 500));

        $('.grant-maternity-care-leave').keyup(delay(function () {
            let $this = $(this);
            let parent = $this.parent().parent();
            let leave_balance = parseFloat(parent.find('.maternity_care_balance').val());
            computeLeave($this, leave_balance)
        }, 500));

        $('.grant-funeral-leave').keyup(delay(function () {
            let $this = $(this);
            let parent = $this.parent().parent();
            let leave_balance = parseFloat(parent.find('.funeral_balance').val());
            computeLeave($this, leave_balance)
        }, 500));

        function computeLeave($this, leave_balance, remaining_place = "") {
            let granted_leave = parseFloat($this.val());
            let parent = $this.parent().parent();
            let absent_days = parseInt(parent.find('.absent-days').val());

            if (granted_leave > leave_balance) {
                granted_leave = Math.floor(parseFloat(leave_balance).toFixed(2));
                $this.val(granted_leave);
            }

            let grant_leaves = parent.find('.grant-leave');
            let total_granted_leaves = 0;
            $.each(grant_leaves, function (index, value) {
                total_granted_leaves += parseInt($(value).val());
            });
            if (parseInt(total_granted_leaves) > parseInt(absent_days)) {
                let excess_days = parseInt(total_granted_leaves) - parseInt(absent_days);
                granted_leave -= excess_days;
                total_granted_leaves -= excess_days;
                $this.val(granted_leave);
            }
            parent.find('.absent_day_remaining').text(absent_days - total_granted_leaves);

            let remaining_leave = leave_balance - granted_leave;
            if (remaining_place != "") {
                remaining_place.text(remaining_leave.toFixed(2));
            }

        }


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

        function updateNetPayment($this) {
            let parent = $($this).parent().parent();
            let grant_home_leave = parent.find('.grant-home-leave').val();
            let grant_sick_leave = parent.find('.grant-sick-leave').val();
            let grant_substitute_leave = parent.find('.grant-substitute-leave').val();
            let grant_maternity_leave = parent.find('.grant-maternity-leave').val();
            let grant_maternity_care_leave = parent.find('.grant-maternity-care-leave').val();
            let grant_funeral_leave = parent.find('.grant-funeral-leave').val();
            let redeem_home_leave = parent.find('.redeem_home_leave').val();
            let redeem_sick_leave = parent.find('.redeem_sick_leave').val();
            let home_loan_installment = parent.find('.home-loan-installment').val();
            let vehicle_loan_installment = parent.find('.vehicle-loan-installment').val();
            let staff_central_id = parent.find('.staff-central-id').val();
            let netpayment = parent.find('.netpayment');
            let deduct_sundry = parent.find('.check-sundry-loan').is(':checked');
            let payroll_id = $('#payroll_id').val()
            if (deduct_sundry) {
                deduct_sundry = 1;
            } else {
                deduct_sundry = 0;
            }
            $.ajax({
                url: '{{route('get-netpayment')}}',
                type: 'POST',
                data: {
                    '_token': '{{csrf_token()}}',
                    'grant_home_leave': grant_home_leave,
                    'grant_sick_leave': grant_sick_leave,
                    'grant_substitute_leave': grant_substitute_leave,
                    'grant_maternity_leave': grant_maternity_leave,
                    'grant_maternity_care_leave': grant_maternity_care_leave,
                    'grant_funeral_leave': grant_funeral_leave,
                    'redeem_home_leave': redeem_home_leave,
                    'redeem_sick_leave': redeem_sick_leave,
                    'home_loan_installment': home_loan_installment,
                    'vehicle_loan_installment': vehicle_loan_installment,
                    'deduct_sundry': deduct_sundry,
                    'staff_central_id': staff_central_id,
                    'payroll_id': payroll_id,
                },
                success: function (data) {
                    netpayment.text(data);
                }
            })

        }

        $('.effect-payment').keyup(delay(function () {
            $this = $(this);
            updateNetPayment($this);
        }, 500));

        $('.check-house-loan,.check-vehicle-loan,.check-sundry-loan').change(function () {
            $this = $(this);
            updateNetPayment($this);
        })


        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()))
            }
        });

        //check uncheck all house loan
        $('#check-all-house-loan').change(function () {
            var checked = $(this).prop('checked');
            if (checked) {
                $('.check-house-loan').prop('checked', true).trigger('change');
            } else {
                $('.check-house-loan').prop('checked', false).trigger('change');
            }
        });

        //check uncheck all
        $('#check-all-vehicle-loan').change(function () {
            var checked = $(this).prop('checked');
            if (checked) {
                $('.check-vehicle-loan').prop('checked', true).trigger('change');
            } else {
                $('.check-vehicle-loan').prop('checked', false).trigger('change');
            }
        });

        //check uncheck all
        $('#check-all-sundry-loan').change(function () {
            var checked = $(this).prop('checked');
            if (checked) {
                $('.check-sundry-loan').prop('checked', true).trigger('change');
            } else {
                $('.check-sundry-loan').prop('checked', false).trigger('change');
            }
        });

        $('#all_misc_amount').bind('propertychange change click keyup input paste', function () { //on any event change
            var misc_amount = $(this).val();
            $('.misc-amount').val(misc_amount);
        });

        $('.loan-installment').keyup(function (e) {
            $this = $(this);
            $this.siblings('.form-error').remove();
            let loan_amount = parseFloat($this.prev().val());
            if (loan_amount != 0) {
                if ($this.val() > loan_amount) {
                    $this.after('<span class="form-error">Installment Amount is greater than Loan Amount</span>');
                    $this.val(loan_amount);
                }
            } else {
                $this.after('<span class="form-error">No Loan Taken/Remaining</span>');
                $this.val(loan_amount);
            }
        });

        $('.use_substitute_leave').click(function () {
            $this = $(this);
            if ($this.is(':checked')) {
                //use sick leaves
                let absent_records = $('.absent-days');
                $.each(absent_records, function (index, value) {
                    let leave_record = $(value);
                    let absent_days = leave_record.val();
                    let parent = leave_record.parent().parent();
                    let present_days = parent.find('.present-days').val();
                    if (absent_days > 0 && present_days >= 1) {
                        let substitute_leave_balance = parseFloat(parent.find('.substitute_balance').val());
                        let use_substitute_leave = 0;
                        if (parseInt(substitute_leave_balance) >= parseFloat(absent_days)) {
                            use_substitute_leave = parseInt(absent_days);
                        } else {
                            use_substitute_leave = parseInt(substitute_leave_balance);
                        }
                        if (use_substitute_leave > 0) {

                            parent.find('.grant-substitute-leave').val(use_substitute_leave);
                            parent.find('.remaining_substitute_leave_balance').text(substitute_leave_balance - parseInt(use_substitute_leave))

                            parent.find('.absent_day_remaining').text(parseInt(absent_days) - parseInt(use_substitute_leave));
                            parent.find('.grant-substitute-leave').trigger('keyup');
                            leave_record = $(value);
                            updateNetPayment(leave_record);
                        }
                    }
                });

            } else {

            }
        })

        $('.use_home_leave').click(function () {
            $this = $(this);
            if ($this.is(':checked')) {
                //use sick leaves
                let absent_records = $('.absent-days');
                $.each(absent_records, function (index, value) {
                    let leave_record = $(value);
                    let absent_days = leave_record.val();
                    let parent = leave_record.parent().parent();
                    let present_days = parent.find('.present-days').val();
                    if (absent_days > 0 && present_days >= 1) {
                        let home_leave_balance = parseFloat(parent.find('.home_balance').val());
                        let use_home_leave = 0;
                        if (parseInt(home_leave_balance) >= parseFloat(absent_days)) {
                            use_home_leave = parseInt(absent_days);
                        } else {
                            use_home_leave = parseInt(home_leave_balance);
                        }
                        if (use_home_leave > 0) {
                            parent.find('.grant-home-leave').val(use_home_leave);
                            parent.find('.remaining_home_leave_balance').text(home_leave_balance - parseInt(use_home_leave))

                            parent.find('.absent_day_remaining').text(parseInt(absent_days) - parseInt(use_home_leave));
                            // console.log(parent.find('.grant-home-leave').keyup());
                            parent.find('.grant-home-leave').keyup();
                            leave_record = $(value);
                            setInterval(updateNetPayment(leave_record), 500)
                        }
                    }
                });
                // $(document).on('keyup');

            } else {

            }
        })
        $(document).on('keyup', '.grant-home-leave',
            function () {
                let $this = $(this);
                let granted_leave = parseFloat($this.val());
                let parent = $this.parent().parent();
                let absent_days = parseInt(parent.find('.absent-days').val());

                let leave_balance = parseFloat(parent.find('.home_balance').val());
                let redeem_leave = parseFloat(parent.find('.redeem_home_leave').val());
                let consumed_leave = parseFloat(granted_leave + redeem_leave);
                if (consumed_leave > leave_balance) {
                    granted_leave = Math.floor(parseFloat(leave_balance - redeem_leave).toFixed(2));
                    $this.val(granted_leave);
                    consumed_leave = parseFloat(redeem_leave) + parseFloat(granted_leave);
                }

                let grant_leaves = parent.find('.grant-leave');
                let total_granted_leaves = 0;
                $.each(grant_leaves, function (index, value) {
                    total_granted_leaves += parseInt($(value).val());
                });
                if (parseInt(total_granted_leaves) > parseInt(absent_days)) {
                    let excess_days = parseInt(total_granted_leaves) - parseInt(absent_days);
                    granted_leave -= excess_days;
                    total_granted_leaves -= excess_days;
                    $this.val(granted_leave);
                }

                let remaining_leave = leave_balance - consumed_leave;

                parent.find('.absent_day_remaining').text(absent_days - total_granted_leaves);
                parent.find('.remaining_home_leave_balance').text(remaining_leave.toFixed(2));
            }
        )
        /*$('.grant-home-leave').keyup(delay(function () {
            let $this = $(this);
            let granted_leave = parseFloat($this.val());
            let parent = $this.parent().parent();
            let absent_days = parseInt(parent.find('.absent-days').val());

            let leave_balance = parseFloat(parent.find('.home_balance').val());
            let redeem_leave = parseFloat(parent.find('.redeem_home_leave').val());
            let consumed_leave = parseFloat(granted_leave + redeem_leave);
            if (consumed_leave > leave_balance) {
                granted_leave = Math.floor(parseFloat(leave_balance - redeem_leave).toFixed(2));
                $this.val(granted_leave);
                consumed_leave = parseFloat(redeem_leave) + parseFloat(granted_leave);
            }

            let grant_leaves = parent.find('.grant-leave');
            let total_granted_leaves = 0;
            $.each(grant_leaves, function (index, value) {
                total_granted_leaves += parseInt($(value).val());
            });
            if (parseInt(total_granted_leaves) > parseInt(absent_days)) {
                let excess_days = parseInt(total_granted_leaves) - parseInt(absent_days);
                granted_leave -= excess_days;
                total_granted_leaves -= excess_days;
                $this.val(granted_leave);
            }

            let remaining_leave = leave_balance - consumed_leave;

            parent.find('.absent_day_remaining').text(absent_days - total_granted_leaves);
            parent.find('.remaining_home_leave_balance').text(remaining_leave.toFixed(2));

        }, 500));*/
        $(document).ready(function () {
            $('.grant-leave').trigger('keyup')
        });
    </script>

    <script src="{{asset('assets/tableHeadFixer/tableHeadFixer.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#fixTable").tableHeadFixer({"left": 8});
        });
    </script>
@endsection
