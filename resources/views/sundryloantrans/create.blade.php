@extends('layouts.default', ['crumbroute' => 'sundryloancreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'sundryloan-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Sundry Information</h5>
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
                                Sundry Type
                            </label>
                            {{ Form::select('sundry_type', $sundry_types, NULL , array('id' => 'sundry_type')) }}
                        </div>

                        <div class="form-group row">
                            <label for="trans_date" class="col-3 col-form-label">
                                Date
                            </label>
                            {{ Form::text('trans_date', null, array('class' => 'form-control nep-date','data-validation' => 'required','id'=>'nep-date1' , 'placeholder' => 'Please enter  Date', 'readonly')) }}
                            <input type="hidden" name="nepdate_eng" id="nep-date1">
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-3 col-form-label">
                                Amount
                            </label>
                            {{ Form::number('amount', null, array('class' => 'form-control','id'=>'amount' , 'placeholder' => 'Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter amount')) }}
                        </div>
                        <div class="form-group row">
                            <label for="no_installment" class="col-3 col-form-label">
                                No of Installment
                            </label>
                            {{ Form::number('no_installment', 1, array('class' => 'form-control','id'=>'no_installment' , 'placeholder' => 'No of Installment',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter a No of Installment'))  }}
                        </div>
                        <div class="form-group row">
                            <label for="installment_amount" class="col-3 col-form-label">
                                Installment Amount
                            </label>
                            {{ Form::text('installment_amount', null, array('class' => 'form-control','id'=>'installment_amount' , 'readonly',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Installment Amount')) }}
                        </div>

                        <div class="form-group row">
                            <label for="notes" class="col-3 col-form-label">
                                Notes
                            </label>
                            {{ Form::textarea('notes', null, array('class' => 'form-control','id'=>'notes', 'placeholder' => 'Notes'))  }}
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
        $("#no_installment").keyup(function () {
            console.log("test");
            var loanAmount = $("#amount").val();
            console.log(loanAmount);
            var no_of_installment = $("#no_installment").val();
            console.log(no_of_installment);
            var totalincome = 0;
            if(no_of_installment > 0){
                totalincome = parseFloat(loanAmount / parseFloat(no_of_installment));
            }
            $("#installment_amount").val(totalincome.toFixed(2));
        });
        $("#amount").keyup(function () {
            console.log("test");
            var loanAmount = $("#amount").val();
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
    </script>
@endsection
