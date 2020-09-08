@extends('layouts.default', ['crumbroute' => 'transfercreate'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'staff-transfer-save'))  }}

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Transfer Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="office_from" class="col-3 col-form-label">
                                Transfer From:
                            </label>
                            <select id="office_from" name="office_from">
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option
                                        value="{{$office->office_id}}" {{($office->office_id==Auth::user()->branch_id)? 'selected':''}}>{{$office->office_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label" id="staff_label">
                                Staff Name
                            </label>
                            <input type="text" id="staff_central_id" placeholder="Select Staff"
                                   name="staff_central_id" class="staff_central_id form-control"></div>
                    </div>

                    <div class="form-group row">
                        <label for="office_id" class="col-3 col-form-label">
                            Transfer to:
                        </label>
                        <select id="office_id" name="office_id" required>
                            <option value="">Select Office</option>
                            @foreach($offices as $office)
                                <option value="{{$office->office_id}}">{{$office->office_name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group row">
                        <label for="shift" id="shift" class="col-3 col-form-label">
                            Shift
                        </label>
                        <div class="shift_container">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="office_id" class="col-3 col-form-label">
                            Transfer Branch Staff ID:
                        </label>
                        {{ Form::text('main_id', null, array('class' => 'form-control','id'=>'main_id' , 'placeholder' => 'Please enter Branch Staff ID',
                           'required' => 'required'))  }}
                    </div>

                    <div class="form-group row">
                        <label for="weekend" class="col-3 col-form-label">
                            Weekend:
                        </label>
                        {{ Form::select('weekend',$weekend_days, null, array('class' => 'form-control','id'=>'weekend' , 'placeholder' => 'Select Weekend'))  }}
                    </div>

                    <div class="form-group row">
                        <label for="from_date" class="col-3 col-form-label">
                            Transfer Date
                        </label>
                        {{ Form::text('from_date', null, array('class' => 'form-control nep-date','id'=>'nep-date1' , 'placeholder' => 'Please enter starting Date',
                         'required' => 'required', 'readonly' => 'readonly', 'data-validation' => 'required'))  }}

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--  Save --}}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <div class="text-right form-control">
                {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
            </div>
        </div>
    </div>
    {{ Form::close()  }}
@endsection

@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        $('#staff_central_id').change(function () {
            let branch_id = $('#office_id').val();
            let staff_id = $(this).val();
            $.ajax({
                url: '{{route('last-main-id-of-branch')}}',
                type: 'POST',
                data: {
                    'branch_id': branch_id,
                    '_token': '{{csrf_token()}}',
                    'staff_id': staff_id
                }, success: function (data) {
                    $('#main_id').val(data);
                }
            });
        });
    </script>

    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20
        });


        $('#office_id').change(function () {
            let branch_id = $(this).val();
            let main_id = $('#main_id').val();
            let staff_id = $('#staff_central_id').val();
            $.ajax({
                url: '{{route('last-main-id-of-branch')}}',
                type: 'POST',
                data: {
                    'branch_id': branch_id,
                    '_token': '{{csrf_token()}}',
                    'staff_id': staff_id
                }, success: function (data) {
                    $('#main_id').val(data);
                }
            });

            $.ajax({
                url: '{{route('get-shift-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch_id,
                    'noScope': 1,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    let shifts = data;
                    $('#shift_id').next().remove();
                    $('#shift_id').remove();
                    $('#shift').after('   <input type="text" id="shift_id" name="shift_id" class="input-sm" \n' +
                        '                                   >');
                    $('#shift_id').prop('disabled', false);
                    $('#shift_id').selectize({
                        valueField: 'id',
                        labelField: 'shift_name',
                        searchField: ['shift_name', 'id'],
                        options: shifts,
                        preload: true,
                        maxItems: 1,
                        create: false,
                        render: {
                            option: function (item, escape) {
                                let status = (item.active == 1) ? 'Active' : 'Inactive';
                                return '<div class="suggestions">' +
                                    '<div> Shift Name: ' + item.original_name + '</div>' +
                                    '<div> Shift Time: ' + item.shift_name + '</div>' +
                                    '<div> ID: ' + item.id + '</div>' +
                                    '<div> Active: ' + status + '</div>' +
                                    '</div>';
                            }
                        },
                        load: function (query, callback) {

                        }
                    });
                    console.log($('#staff').next().next().addClass('removeit'));

                    var $select = $('#shift_id').selectize();  // This initializes the selectize control
                    var selectize = $select[0].selectize; // This stores the selectize object to a variable (with name 'selectize')

                    {{--selectize.setValue('{{$staffmain->shift_id}}', false);--}}
                }
            });


        })

        $(document).ready(loadStaff())

        $('#office_from').change(function () {
            $('.staff_central_id').remove();
            $('#staff_label').after('<input type="text" id="staff_central_id" placeholder="Select Staff" name="staff_central_id" class="staff_central_id form-control">');

            loadStaff();
        });

        function loadStaff() {
            branch_id = $('#office_from').val();
            $.ajax({
                url: '{{ route('get-staff') }}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'branch_id': branch_id,
                    'limit': 15
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
                                url: '{{ route('get-staff') }}?search=' + encodeURIComponent(query) + '&limit=15' + '&branch_id=' + branch_id,
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
@endsection
