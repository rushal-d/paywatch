@extends('layouts.default', ['crumbroute' => 'houseloancreate'])
@section('title', $title)
@section('content')
    {{ Form::open(array('route' => 'houseloan-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Loan Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" required>

                        </div>
                        <div class="form-group row">
                            <label for="trans_date" class="col-3 col-form-label">
                                Loan Issue Date
                            </label>

                            {{ Form::text('trans_date', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date', 'readonly'
                                ))  }}
                            <input type="hidden" name="nepdate_eng" id="nep-date1">

                        </div>
                        <div class="form-group row">
                            <label for="loan_amount" class="col-3 col-form-label">
                                Loan Amount
                            </label>

                            {{ Form::number('loan_amount', null, array('class' => 'form-control','id'=>'loan_amount' , 'placeholder' => 'Loan  Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Loan Amount'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="no_installment" class="col-3 col-form-label">
                                No Of Installment
                            </label>

                            {{ Form::number('no_installment', null, array('class' => 'form-control','id'=>'no_installment' , 'placeholder' => 'No of Installment',
                            'readonly' => 'readonly',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a No of Installment'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="installment_amount" class="col-3 col-form-label">
                                Installment Amount
                            </label>

                            {{ Form::text('installment_amount', null, array('class' => 'form-control','id'=>'installment_amount' ,'placeholder' => 'No of Installment',
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
                        <p id="msg"></p>
                        {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg','id'=>'save'))}}
                    </div>
                </div>
            </div>
        </div>
        {{-- Right Sidebar  --}}
        <div class="col-md-5 col-sm-12">


        </div>
        {{-- End of sidebar --}}

    </div>
    {{ Form::close()  }}
@endsection



@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        var staffs = <?php echo $staffs ?>;
        $('#staff_central_id').selectize({
            valueField: 'id',
            labelField: 'name_eng',
            searchField: ['name_eng', 'main_id'],
            options: staffs,
            preload: true,
            maxItems: 1,
            create: false,
            render: {
                option: function (item, escape) {
                    return '<div class="suggestions"><div> Name: ' + item.name_eng + '</div>' +
                        '<div> Branch ID: ' + item.main_id + '</div>' +
                        '<div> Branch: ' + item.branch.office_name+ '</div>' +
                        '<div> CID: ' + item.staff_central_id+ '</div>' +
                        '</div>';
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query),
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

        $('#staff_central_id').change(function () {
            var staff_id = $(this).val();
            $.ajax({
                url: '{{route('house-loan-check')}}',
                type: 'post',
                data: {
                    'id': staff_id,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    if (data == 1) {
                        $('#msg').text('The staff has already taken house loan');
                        $('#save').prop('disabled', true);
                    }
                    else {
                        $('#msg').hide();
                        $('#save').prop('disabled', false);
                    }
                }
            });
        })

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
        $("#no_installment").blur(function () {
            var loanAmount = $("#loan_amount").val();
            var no_of_installment = $("#no_installment").val();
            var totalincome = 0;
            if (no_of_installment > 0) {
                totalincome = parseFloat(loanAmount / parseFloat(no_of_installment));
            }
            $("#installment_amount").val(totalincome.toFixed(2));
        });

        $("#installment_amount").blur(function () {
            var loanAmount = $("#loan_amount").val();
            var installment_amount = $("#installment_amount").val();
            var totalincome = 0;
            if (installment_amount > 0) {
                totalincome = Math.ceil(loanAmount / parseFloat(installment_amount));
            }
            $("#no_installment").val(totalincome.toFixed(2));
        });

        $("#loan_amount").blur(function () {
            var loanAmount = $("#loan_amount").val();
            var installment_amount = $("#installment_amount").val();
            var totalincome = 0;
            if (installment_amount > 0) {
                totalincome = Math.ceil(loanAmount / parseFloat(installment_amount));
            }
            $("#no_installment").val(totalincome.toFixed(2));
        });
    </script>
@endsection
