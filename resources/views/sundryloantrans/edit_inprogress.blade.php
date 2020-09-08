@extends('layouts.default', ['crumbroute' => 'sundryloanedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('sundryloan-update-inprogress',$sundryloan->id), 'class' => 'educationform' ))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Sundry Loan Payment in Progress Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                                {{--<span class="badge badge-pill badge-danger">*</span>--}}
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                   value="{{$sundryloan->staff_central_id}}" required>
                        </div>

                        <div class="form-group row">
                            <label for="trans_date" class="col-3 col-form-label">
                                Sundry Type
                            </label>
                            {{ Form::select('sundry_type', $sundry_types, $sundryloan->transaction_type_id , array('id' => 'sundry_type','disabled')) }}
                        </div>

                        <div class="form-group row">
                            <label for="trans_date" class="col-3 col-form-label">
                                Date
                            </label>

                            {{ Form::text('trans_date', $sundryloan->transaction_date, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date', 'readonly'
                                 ))  }}

                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-3 col-form-label">
                                Recorded Amount
                            </label>
                            {{ Form::number('amount', $sundryloan->amount, array('class' => 'form-control','id'=>'amount' , 'placeholder' => 'Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter amount','readonly')) }}
                        </div>
                        <div class="form-group row">
                            <label for="no_installment" class="col-3 col-form-label">
                                Recorded No of Installment
                            </label>
                            {{ Form::number('no_installment', $sundryloan->no_installment, array('class' => 'form-control','id'=>'no_installment' , 'placeholder' => 'No of Installment',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter a No of Installment','readonly'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="installment_amount" class="col-3 col-form-label">
                                Recorded Installment Amount
                            </label>
                            {{ Form::text('installment_amount', $sundryloan->installment_amount, array('class' => 'form-control','id'=>'installment_amount' , 'readonly',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Installment Amount')) }}
                        </div>

                        <table class="table table-bordered">
                            <thead>
                            <th>Nos. of Paid Installments</th>
                            <th>Amount Per Installment</th>
                            <th>Total Amount Paid</th>
                            <th>Nos. of Remaining Installments</th>
                            <th>Amount Per Installment</th>
                            <th>Total Amount Remaining</th>
                            </thead>
                            <tbody>
                            <tr>
                                @if(App\SundryType::isCR($sundryloan->transaction_type_id))
                                    <td>{{$sundryloan->dr_installment}}</td>
                                    <td>{{$sundryloan->dr_amount}}</td>
                                    <td>{{$sundryloan->dr_balance}}</td>
                                    <td>{{$sundryloan->cr_installment-$sundryloan->dr_installment}}</td>
                                    <td>{{$sundryloan->cr_amount}}</td>
                                    <td>{{$sundryloan->cr_balance-$sundryloan->dr_balance}}</td>
                                @else
                                    <td>{{$sundryloan->cr_installment}}</td>
                                    <td>{{$sundryloan->cr_amount}}</td>
                                    <td>{{$sundryloan->cr_balance}}</td>
                                    <td>{{$sundryloan->dr_installment-$sundryloan->cr_installment}}</td>
                                    <td>{{$sundryloan->dr_amount}}</td>
                                    <td>{{$sundryloan->dr_balance-$sundryloan->cr_balance}}</td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                        <p align="justify">Note: Since some installments has already been paid the attempt to edit with
                            reconcile the previous
                            transaction i.e will make the recorded amount as paid and new record for the edited amount
                            will be created.
                        </p>
                        <div class="form-group row">
                            <label for="amount" class="col-3 col-form-label">
                                New Amount
                            </label>
                            {{ Form::number('new_amount', null, array('class' => 'form-control','id'=>'new_amount' , 'placeholder' => 'Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter amount')) }}
                        </div>
                        <div class="form-group row">
                            <label for="no_installment" class="col-3 col-form-label">
                                New No of Installment
                            </label>
                            {{ Form::number('new_no_installment', 1, array('class' => 'form-control','id'=>'new_no_installment' , 'placeholder' => 'No of Installment',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter a No of Installment'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="installment_amount" class="col-3 col-form-label">
                                New Installment Amount
                            </label>
                            {{ Form::text('new_installment_amount', null, array('class' => 'form-control','id'=>'new_installment_amount' , 'readonly',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Installment Amount')) }}
                        </div>


                        <div class="form-group row">
                            <label for="notes" class="col-3 col-form-label">
                                Notes
                            </label>
                            {{ Form::textarea('notes', $sundryloan->notes, array('class' => 'form-control','id'=>'notes', 'placeholder' => 'Notes'))  }}
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
        $(document).ready(loadStaff())

        function loadStaff() {
            $.ajax({
                url: '{{ route('get-staff') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'limit': 15,
                    'staff_central_id': '{{$sundryloan->staff_central_id}}'
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

        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))

            }
        });
    </script>
    <script>
        $("#new_no_installment").keyup(function () {
            console.log("test");
            var loanAmount = $("#new_amount").val();
            console.log(loanAmount);
            var no_of_installment = $("#new_no_installment").val();
            console.log(no_of_installment);
            var totalincome = 0;
            if (no_of_installment > 0) {
                totalincome = parseFloat(loanAmount / parseFloat(no_of_installment));
            }
            $("#new_installment_amount").val(totalincome.toFixed(2));
        });
        $("#new_amount").keyup(function () {
            var loanAmount = $("#new_amount").val();
            console.log(loanAmount);
            var no_of_installment = $("#new_no_installment").val();
            console.log(no_of_installment);
            var totalincome = 0;
            if (no_of_installment > 0) {
                totalincome = parseFloat(loanAmount / parseFloat(no_of_installment));
            }
            $("#new_installment_amount").val(totalincome.toFixed(2));
        });
    </script>
@endsection
