@extends('layouts.default', ['crumbroute' => 'leavebalanceedit'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => array('leavebalance-update', $balance->id)))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Edit Leave Balance Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <input type="text" value="{{ $balance->staff_central_id  }}" id="staff_central_id" name="staff_central_id" class="input-sm" required>

                        </div>

                        <div class="form-group row">
                            <label for="fy_year" class="col-3 col-form-label">
                                Select Leave
                            </label>
                            {{ Form::select('leave_id', $leavetypes, $balance->leave_id, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="date_np" class="col-3 col-form-label">
                                Date
                            </label>
                            {{ Form::text('date_np', $balance->date_np, array('class' => 'form-control nep-date','id'=>'nep-date' , 'placeholder' => 'Date', 'readonly')) }}
                            <input type="hidden" value="{{ $balance->date_np }}" id="date" name="date" >
                        </div>

                        <div class="form-group row">
                            <label for="fy_year" class="col-3 col-form-label">
                                Fiscal year (FY)
                            </label>
                            {{ Form::select('fy_id', $fiscalyear, $balance->fy_id, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-3 col-form-label">Description</label>
                            {{ Form::text('description', $balance->description, array('class' => 'form-control','id'=>'description' , 'placeholder' => 'Description',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter description!'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="consumption" class="col-3 col-form-label">Consumption</label>
                            {{ Form::text('consumption', $balance->consumption, array('class' => 'form-control','id'=>'consumption' , 'placeholder' => 'Consumption',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter consumption!'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="earned" class="col-3 col-form-label">Earned</label>
                            {{ Form::text('earned', $balance->earned, array('class' => 'form-control','id'=>'earned' , 'placeholder' => 'Earned',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter earned!'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="balance" class="col-3 col-form-label">Balance</label>
                            {{ Form::text('balance', $balance->balance, array('class' => 'form-control','id'=>'balance' , 'placeholder' => 'Balance',
                             'data-validation' => 'required', 'data-validation-error-msg' => 'Please enter balance!'))  }}
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
        $(document).ready(loadStaff())

        function loadStaff() {
            $.ajax({
                url: '{{ route('get-staff') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'limit': 15,
                    'staff_central_id': '{{$balance->staff_central_id}}'
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
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20 ,
            onChange: function(e){
                $('#nep-date').next().val(BS2AD($('#nep-date').val()))
            }
        });
    </script>
@endsection
