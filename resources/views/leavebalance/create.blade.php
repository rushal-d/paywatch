@extends('layouts.default', ['crumbroute' => 'leavebalancecreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'leavebalance-save'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Add Leave Balance Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" required>

                        </div>

                        <div class="form-group row">
                            <label for="leave_id" class="col-3 col-form-label">Leave Name</label>
                            {{ Form::select('leave_id', $leavetypes, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="date_np" class="col-3 col-form-label">
                                Date
                            </label>
                            {{ Form::text('date_np', null, array('class' => 'form-control nep-date','id'=>'nep-date' , 'placeholder' => 'Date', 'readonly')) }}
                            <input type="hidden" id="date" name="date">
                        </div>

                        <div class="form-group row">
                            <label for="fy_year" class="col-3 col-form-label">
                                Fiscal year (FY)
                            </label>
                            {{ Form::select('fy_id', $fiscalyear, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-3 col-form-label">Description</label>
                            {{ Form::text('description', null, array('class' => 'form-control','id'=>'description' , 'placeholder' => 'Description',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter description!'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="consumption" class="col-3 col-form-label">Consumption</label>
                            {{ Form::text('consumption', 0, array('class' => 'form-control','id'=>'consumption' , 'placeholder' => 'Consumption',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter consumption!','step'=>0.01))  }}
                        </div>

                        <div class="form-group row">
                            <label for="earned" class="col-3 col-form-label">Earned</label>
                            {{ Form::text('earned', 0, array('class' => 'form-control','id'=>'earned' , 'placeholder' => 'Earned',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter earned!','step'=>0.01))  }}
                        </div>

                        <div class="form-group row">
                            <label for="balance" class="col-3 col-form-label">Balance</label>
                            {{ Form::number('balance', 0, array('class' => 'form-control','id'=>'balance' , 'placeholder' => 'Balance',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter balance!','step'=>0.01))  }}
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
    </div>
    {{ Form::close()  }}
@endsection
@section('script')
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
                        '<div> Branch: ' + item.branch.office_name + '</div>' +
                        '<div> CID: ' + item.staff_central_id + '</div>' +
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
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date').next().val(BS2AD($('#nep-date').val()))
            }
        });
    </script>
@endsection
