@extends('layouts.default', ['crumbroute' => 'staffedit'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
    <style>
        .mydrop {
            width: 305px;
            margin-left: -49px;
        }

        .required-field {
            color: red;
        }


    </style>
@endsection
@section('content')
    @include('staffmain.staff-edit-nav')
    <form method="post" action="{{ route('staff-payment-store',$staffmain->id) }}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}">

        <div class="row">
            <div class="col-md-7 col-sm-12">

                {{--staff payment start--}}
                <div class="basic-info card">
                    <h5 class="card-header">Staff Payment : {{$staffmain->name_eng}} -
                        [CID: {{$staffmain->staff_central_id}}] - [Branch
                        ID: {{$staffmain->main_id}} {{$staffmain->branch->office_name ?? ''}}]</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="row no-gutters two-fields">
                                <div class="col-md-6 col-sm-12">
                                    <label for="cash" class="col-form-label">Cash</label>
                                    <input type="checkbox" class="cash_payment"
                                           @if(empty($staffmain->bank_id) || empty($staffmain->acc_no)) checked @endif>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="pay_type" class="col-form-label">Bank</label>
                                    {!! Form::select('bank_id',$banks,$staffmain->bank_id,['placeholder'=>'Select Bank','id'=>'bank_id']) !!}

                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label for="acc_no" class="col-form-label">Profund Account</label>
                                    {{ Form::text('profund_acc_no', $staffmain->profund_acc_no, array('class' => 'form-control', 'placeholder' => 'Input Profund Account Number'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="acc_no" class="col-form-label">Bank Account</label>
                                    {{ Form::text('acc_no', $staffmain->acc_no, array('class' => 'form-control', 'placeholder' => 'Input Account Number','id'=>'acc_no','data-validation'=>'requiredIfBankSelected requireBankIfHasAccNo'))  }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <label for="social_security_fund_acc_no" class="col-form-label">Social Security
                                        Account</label>
                                    {{ Form::text('social_security_fund_acc_no', $staffmain->social_security_fund_acc_no, array('class' => 'form-control', 'placeholder' => 'Input Social Security Account Number'))  }}
                                </div>


                                <div class="col-md-6 col-sm-12">
                                    <label for="pan_number" class="col-form-label">Staff PAN Number</label>
                                    {{ Form::text('pan_number', $staffmain->pan_number, array('class' => 'form-control', 'placeholder' => 'Input Staff PAN Number','id'=>'pan_number'))  }}
                                </div>


                                <div class="col-md-6 col-sm-12">
                                    <label for="pan_number" class="col-form-label">Deduct Levy</label>
                                    {{ Form::select('deduct_levy', ["No","Yes"],$staffmain->deduct_levy, array('class' => 'form-control', 'placeholder' => 'Select One','id'=>'deduct-levy'))  }}
                                </div>

                                @if(strcasecmp($organizationSetup->organization_code,'bbsm')!==0)
                                    <div class="col-md-6 col-sm-12">
                                        <label for="pan_number" class="col-form-label">Default Cit Deduction</label>
                                        {{ Form::number('default_cit_deduction_amount', $staffmain->default_cit_deduction_amount, array('class' => 'form-control positive-integer-number', 'placeholder' => 'Input Default Cit Deduction','id'=>'default_cit_deduction_amount'))  }}
                                    </div>
                                @endif
                            </div>

                            <div class="row no-gutters two-fields checkbox-inline">
                                @foreach($allowances as $allowance)
                                    <div class="col-md-6 col-sm-6">
                                        <label class="col-form-label main-label">{{$allowance->allow_title}}</label>
                                        @php $prev_allowance=$staffmain->payment->where('allow_id',$allowance->allow_id)->sortByDesc('effective_from')->first(); @endphp
                                        @if($allowance->show_on_form)
                                            <input type="text" value="{{$prev_allowance->amount ?? 0}}"
                                                   name="allowance[{{$allowance->allow_id}}][amount]"
                                                   data-default="{{$prev_allowance->amount ?? $allowance->allow_amt}}"
                                                   class="allowance-field">
                                        @else
                                            <input type="hidden" value="{{0}}"
                                                   name="allowance[{{$allowance->allow_id}}][amount]"
                                                   class="allowance-field">
                                        @endif
                                    </div>
                                    <div class="col-md-2 col-sm-2">
                                        <label class="col-form-label">Allow</label>
                                        <input type="checkbox" name="allowance[{{$allowance->allow_id}}][allow]"
                                               class="form-control-check-input toggle-default-allowance" value="1"
                                               @if(!empty($prev_allowance) && ($prev_allowance->allow==1)) checked @endif>
                                    </div>
                                    <div class="col-md-4 col-sm-4">

                                        <input type="text"
                                               value="{{$prev_allowance->effective_from_np ?? \App\Helpers\BSDateHelper::AdToBs('-',date('Y-m-d'))}}"
                                               name="allowance[{{$allowance->allow_id}}][effective_date]"
                                               id="nep-date-{{$allowance->allow_id}}"
                                               class="nep-date" readonly>

                                    </div>


                                @endforeach

                            </div>
                        </div>


                    </div>
                </div>
                {{--staff payment end--}}

                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right form-control">
                            {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="basic-info card">
                    <h5 class="card-header">Staff Payment History</h5>
                    <div class="card-block">
                        <div class="card-text">
                            {{Form::select('allowance_id',$allowances->pluck('allow_title','allow_id'),null,['class'=>'allowance-select','placeholder'=>'Select Allowance'])}}
                            @foreach($allowances as $allowance)
                                @php $allowance_histories=$staffmain->payment->where('allow_id',$allowance->allow_id)->sortByDesc('effective_from'); @endphp
                                <table
                                    class="table table-bordered allowance-history-{{$allowance->allow_id}} allowance-history"
                                    style="display: none">

                                    <tr>
                                        <td><b>Effective Date</b></td>
                                        <td><b>Allowed</b></td>
                                        <td><b>Amount</b></td>
                                        <td><b>Action</b></td>
                                    </tr>
                                    @if($allowance_histories->count()>0)
                                        @foreach($allowance_histories as $allowance_history)
                                            <tr>
                                                <td>{{$allowance_history->effective_from_np}}</td>
                                                <td>{{($allowance_history->allow==1)? 'Allowed':'Not Allowed'}}</td>
                                                <td>{{$allowance_history->amount}}</td>
                                                <td>
                                                    <a href="javascript:void(0);" class="text-danger delete"
                                                       data-id="{{$allowance_history->id}}"> <i
                                                            class="fa fa-minus"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">This allowance is not provided to this staff yet.</td>
                                        </tr>
                                    @endif
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </form>

@endsection
@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>

        $.formUtils.addValidator({
            name: 'requiredIfBankSelected',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value == '') {
                    let bank_id = $('#bank_id').val();
                    return (bank_id === '');
                }
                return true;
            },
            errorMessage: 'Must enter Account Number if bank selected!',
            errorMessageKey: ''
        });

        $.formUtils.addValidator({
            name: 'requireBankIfHasAccNo',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value != '') {
                    let bank_id = $('#bank_id').val();
                    return (bank_id != '');
                }
                return true;
            },
            errorMessage: 'Must select bank if has account number!',
            errorMessageKey: ''
        });

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 50 // Options | Number of years to show
        });

        $('.toggle-default-allowance').click(function () {
            $this = $(this);
            if ($this.is(':checked')) {
                if ($this.parent().prev().find('.allowance-field').val() == '' || $this.parent().prev().find('.allowance-field').val() == 0) {
                    let default_value = $this.parent().prev().find('.allowance-field').data('default');
                    $this.parent().prev().find('.allowance-field').val(default_value);
                }

            } else {
                $this.parent().prev().find('.allowance-field').val(0);
            }
        });

        $('.cash_payment').click(function () {
            $this = $(this);
            if ($this.is(':checked')) {
                var $select = $('#bank_id').selectize();
                var control = $select[0].selectize;
                control.clear();

                $('#acc_no').val('');
            }
        });

        $('.allowance-select').change(function (e) {
            e.preventDefault();
            $this = $(this);
            $('.allowance-history').hide();
            $('.allowance-history-' + $this.val()).show();

        })
    </script>
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
        //delete
        $('body').on('click', '.delete', function () {
            $this = $(this)
            vex.dialog.confirm({
                message: 'Are you sure you want to delete?',
                callback: function (value) {
                    console.log('Callback value: ' + value + $this.data('id'));
                    if (value) { //true if clicked on ok
                        $.ajax({
                            type: "DELETE",
                            url: '{{ route('staff-payment-delete') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted');
                                    $this.parent().parent().remove();
                                } else {
                                    vex.dialog.alert(response)
                                }
                            },
                            error: function (response) {
                                vex.dialog.alert(response)
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
