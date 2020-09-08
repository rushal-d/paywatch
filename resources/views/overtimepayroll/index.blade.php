@extends('layouts.default', ['crumbroute' => 'payrollcreate'])
@section('title', $title)
@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="row">
        <div class="col-md-7 col-sm-12">
            {!!Form::open(['route' => 'overtime-payroll-calculate', 'method' => 'GET'])!!}
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Overtime Payroll Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="branch_id" class="col-3 col-form-label">
                                Branch
                            </label>
                            {!! Form::select('branch_id',$branches,old('branch_id'),['class'=>'form-control','placeholder'=>'Select Branch','required']) !!}
                        </div>
                        <div class="form-group row">
                            <label for="fiscal_year_id" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_year_id ?? old('fiscal_year_id'),array('id'=>'fiscal_year_id', 'placeholder' => 'Fiscal Year') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Month
                            </label>
                            {!! Form::select('month_id', $months , old('month_id'), array( 'id'=>'month_id', 'placeholder'=>'Select a Month') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="from_date_np" class="col-3 col-form-label">Date From</label>
                            {{ Form::text('from_date_np', old('from_date_np'), array('autocomplete' => 'off', 'class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'From', 'readonly' => 'readonly' ))  }}
                            <input type="hidden" id="from_date" name="from_date">
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                Date To
                            </label>
                            {{ Form::text('to_date_np', old('to_date_np'), array('autocomplete' => 'off', 'class' => 'form-control nep-date', 'required' => 'required', 'id'=>'nep-date2' , 'placeholder' => 'To', 'readonly' => 'readonly')) }}
                            <input type="hidden" id="to_date" name="to_date">
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                Payroll Month
                            </label>
                            {{ Form::text('salary_month_name', old('salary_month_name'), array('readonly' => 'readonly', 'id'=> 'salary_month_name', 'class' => 'form-control'))  }}
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                Payroll Total Days
                            </label>
                            {{ Form::text('total_days', old('total_days'), array('readonly' => 'readonly', 'id'=> 'total_days', 'class' => 'form-control'))  }}
                        </div>
                        <input type="hidden" name="salary_month" id="salary_month" value="{{old('salary_month')}}">
                    </div>
                </div>
            </div>
            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        <button class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>



@endsection


@section('script')

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
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


        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()))

                //calculate days
                calculateDays();
            }
        });

        function calculateDays() {
            //calculate days
            var date_from = $('#nep-date1').next().val();
            var date_to = $('#nep-date2').next().val();
            //also check if contains NaN
            if (date_from && date_from.indexOf('NaN') < 0 && date_to && date_to.indexOf('NaN') < 0) {
                var diff_days = daydiff(parseDate(date_from), parseDate(date_to)) + 1
                if (diff_days > 0) {
                    $('#total_days').val(diff_days);
                    var mdy = $('#nep-date1').val().split('-');
                    var month = mdy[1];
                    var months = new Array(
                        'Baishakh', 'Jestha', 'Asar', 'Shrawan', 'Bhadra', 'Ashwin', 'Kartik',
                        'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'
                    );
                    $('#salary_month_name').val(months[month - 1]);
                    $('#salary_month').val(month);
                } else {
                    $('#total_days').val(0);
                    toastr.error('Please check date from and to!', 'Error!')
                }
            }
        }

        function parseDate(str) {
            var mdy = str.split('-');
            return new Date(mdy[0], mdy[1] - 1, mdy[2]);
        }

        function daydiff(first, second) {
            return Math.round((second - first) / (1000 * 60 * 60 * 24));
        }

        //show date from date to by month id

        $(function () {

            $('#fiscal_year_id').change(function () {
                //get the first day of the month
                const selected_month = $('#month_id').val();
                var selected_year = $(this).val();
                if (selected_year === '' || selected_year === undefined) {
                    selected_year = '{{ $current_fiscal_year_id }}'
                }
                $.ajax({
                    url: '{{route('getmonthdatefromto')}}',
                    type: 'post',
                    data: {
                        'selected_month': selected_month,
                        'selected_year': selected_year,
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (data) {
                        if (data.status) {
                            $('#nep-date1').val(data.start_date_np);
                            $('#nep-date2').val(data.end_date_np);
                            $('#from_date').val(data.start_date_en);
                            $('#to_date').val(data.end_date_en);
                            calculateDays()
                        }
                    }
                });
            });

            $('#month_id').change(function () {
                //get the first day of the month
                const selected_month = $('#month_id').val();
                var selected_year = $('#fiscal_year_id').val();
                console.log(selected_year);
                if (selected_year === '' || selected_year === undefined) {
                    selected_year = '{{ $current_fiscal_year_id }}'
                }
                $.ajax({
                    url: '{{route('getmonthdatefromto')}}',
                    type: 'post',
                    data: {
                        'selected_month': selected_month,
                        'selected_year': selected_year,
                        '_token': '{{csrf_token()}}'
                    },
                    success: function (data) {
                        if (data.status) {
                            $('#nep-date1').val(data.start_date_np);
                            $('#nep-date2').val(data.end_date_np);
                            $('#from_date').val(data.start_date_en);
                            $('#to_date').val(data.end_date_en);
                            calculateDays()
                        }
                    }
                });
            });

        });
    </script>
@endsection
