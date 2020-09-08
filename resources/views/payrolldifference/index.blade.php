@extends('layouts.default', ['crumbroute' => 'bankstatement'])
@section('title', $title)
@section('content')
    <form action="{{ route('payroll-difference-show') }}" method="get" id="payroll_difference">
{{--    <form action="{{ route('payroll-difference-single-confirm') }}" method="get" id="payroll_difference">--}}

        <div class="row">
            <div class="col-md-6 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">{{ $title }} Reports</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="branch_id" class="col-3 col-form-label">
                                    Branch
                                </label>
                                {!! Form::select('branch_id',$branches,old('branch_id',\Auth::user()->branch_id ?? ''),['class'=>'form-control','id'=>'branch_id','placeholder'=>'Select Branch','required']) !!}
                            </div>

                            <div class="form-group row">
                                <label for="staff_central_id" class="col-3 col-form-label" id="staff_label">
                                    Staff
                                </label>
                                <input type="text" id="staff_central_id" placeholder="Select Staff"
                                       name="staff_central_id" class="staff_central_id form-control"></div>
                            <div class="form-group row">
                                <label for="fiscal_year_id" class="col-3 col-form-label">
                                    Fiscal Year
                                </label>
                                {!! Form::select('fiscal_year_id',$fiscal_years,old('fiscal_year_id'),['class'=>'form-control','id'=>'fiscal_year_id','placeholder'=>'Select Fiscal Year','required']) !!}
                            </div>

                            <div class="form-group row">
                                <label for="month" class="col-3 col-form-label payroll_label">
                                    Payroll
                                </label>
                                {!! Form::select('payroll_id',$payrolls,null,['class'=>'form-control payroll_id','id'=>'payroll_id','placeholder'=>'Select Payroll ...','required']) !!}
                            </div>

                        </div>
                    </div>
                    {{--  Save --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right form-control">
                                {{--                                {{ Form::submit('Submit',array('class'=>'btn btn-success'))}}--}}
                                <button type="button" class="btn btn-success calculate-difference">Calculate
                                    Difference
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{--{{ Form::close()  }}--}}
        </div>
    </form>
@endsection

@section('script')
    <script>
        $('#fiscal_year_id,#branch_id').change(function () {

            $('.payroll_id').remove();
            $('.payroll_label').after('<input type="text" id="payroll_id" placeholder="Select Payroll" name="payroll_id" class="payroll_id form-control">');

            $.ajax({
                url: '{{route('get-payroll-name')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'fiscal_year_id': $('#fiscal_year_id').val(),
                    'branch_id': $('#branch_id').val(),
                    'confirmed': 1
                },
                success: function (data) {
                    $('#payroll_id').selectize({
                        valueField: 'id',
                        labelField: 'payroll_name',
                        searchField: ['payroll_name'],
                        options: data,
                        preload: true,
                        maxItems: 1,
                        create: false,
                        render: {},
                        load: function (query, callback) {

                        }
                    });
                }
            });
        });
        $(document).ready(loadStaff())

        $('#branch_id').change(function () {
            $('.staff_central_id').remove();
            $('#staff_label').after('<input type="text" id="staff_central_id" placeholder="Select Staff" name="staff_central_id" class="staff_central_id form-control">');

            loadStaff();
        });

        function loadStaff() {
            branch_id = $('#branch_id').val();
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

        $('.calculate-difference').click(function (e) {
            $('#loader').show();
            let staff_central_id = $('#staff_central_id').val();
            let branch_id = $('#branch_id').val();
            let payroll_id = $('#payroll_id').val();
            if (staff_central_id != '' && branch_id != '' && payroll_id != '') {
                $.ajax({
                    url: '{{route('fetch-to-detail')}}',
                    type: 'POST',
                    data: {
                        '_token': '{{csrf_token()}}',
                        'staff_central_ids': [{id: staff_central_id}],
                        'branch_id': branch_id,
                        'payroll_id': payroll_id
                    }, success: function (data) {
                        if (data == true) {
                            per = 100;
                            $('.progress-bar').css('width', per + '%')
                            $('#loader').hide();
                            $('#payroll_difference').submit();
                        } else {
                            return false;
                            $('#loader').hide();
                            vex.dialog.alert("Error Occured! Please try again or contact the development team!")
                        }
                    }, error: function () {
                        $('#loader').hide();
                        vex.dialog.alert("Error Occured! Please try again or contact the development team!")
                    }
                });
            }

        })

    </script>
@endsection
