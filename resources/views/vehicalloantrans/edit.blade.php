@extends('layouts.default', ['crumbroute' => 'vehicalloanedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('vehicalloan-update',$vehicalloan->vehical_id), 'class' => 'educationform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Vehical Loan Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                   value="{{$vehicalloan->staff_central_id}}" required>
                        </div>
                        <div class="form-group row">
                            <label for="trans_date" class="col-3 col-form-label">
                                Loan Issue Date
                            </label>
                            {{ Form::text('trans_date', $vehicalloan->trans_date, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date', 'readonly'
                                  ,'data-validation' => 'required',
                                  'data-validation-error-msg' => 'Please enter Starting date'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="loan_amount" class="col-3 col-form-label">
                                Loan Amount
                            </label>
                            {{ Form::number('loan_amount',  $vehicalloan->loan_amount, array('class' => 'form-control','id'=>'loan_amount' , 'placeholder' => 'Loan  Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Loan Amount'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="no_installment" class="col-3 col-form-label">
                                No Of Installment
                            </label>
                            {{--{{ Form::label('title', 'Title') }}--}}
                            {{ Form::number('no_installment',  $vehicalloan->no_installment, array('class' => 'form-control','id'=>'no_installment' , 'placeholder' => 'No of Installment',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a No of Installment'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="installment_amount" class="col-3 col-form-label">
                                Installment Amount
                            </label>
                            {{ Form::text('installment_amount',  $vehicalloan->installment_amount, array('class' => 'form-control','id'=>'installment_amount' , 'readonly','placeholder' => 'No of Installment',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a No of Installment'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{ Form::close()  }}
@endsection

@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function(e){
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))

            }
        });
    </script>
    <script>
        $(document).ready(loadStaff())

        function loadStaff() {
            $.ajax({
                url: '{{ route('get-staff') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'limit': 15,
                    'staff_central_id': '{{$vehicalloan->staff_central_id}}'
                },
                success: function (data) {
                    $('#staff_central_id').selectize({
                        valueField: 'id',
                        labelField: 'name_eng',
                        searchField: ['name_eng', 'main_id'],
                        options: data['staffs'],
                        preload: true,
                        maxItems: 1,
                        create: false,
                        render: {
                            option: function (item, escape) {
                                return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                                    '<div> Branch ID: ' + item.main_id + '</div>' +
                                    '<div> Branch: ' + item.branch.office_name + '</div>' +
                                    '<div> CID: ' + item.staff_central_id + '</div>' +
                                    '</div>';
                            }
                        },
                        load: function (query, callback) {
                            if (!query.length) return callback();
                            $.ajax({
                                url: '{{ route('get-staff') }}',
                                data: {
                                    'search': encodeURIComponent(query),
                                    'limit': 15,
                                },
                                type: 'GET',
                                error: function () {
                                    callback();
                                },
                                success: function (res) {
                                    callback(res.staffs);
                                }
                            });
                        }
                    });
                }
            });
        }
    </script>
    <script>
        $("#no_installment").blur(function () {
            console.log("test");
            var loanAmount = $("#loan_amount").val();
            console.log(loanAmount);
            var no_of_installment = $("#no_installment").val();
            console.log(no_of_installment);
            var totalincome = 0;
            if(no_of_installment > 0){
                totalincome = parseFloat(loanAmount / parseFloat(no_of_installment));
            }
            $("#installment_amount").val(totalincome.toFixed(2));
        });
        $("#loan_amount").blur(function () {
            console.log("test");
            var loanAmount = $("#loan_amount").val();
            console.log(loanAmount);
            var no_of_installment = $("#no_installment").val();
            console.log(no_of_installment);
            var totalincome = 0;
            if(no_of_installment > 0){
                totalincome = parseFloat(loanAmount / parseFloat(no_of_installment));
            }
            $("#installment_amount").val(totalincome.toFixed(2));
        });
    </script>
@endsection
