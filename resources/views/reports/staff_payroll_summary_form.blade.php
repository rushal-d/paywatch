@extends('layouts.default', ['crumbroute' => 'staff_payroll_summary'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'staff_payroll_summary-show','method'=>'GET'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Payroll Summary</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="branch_id" class="col-3 col-form-label">
                                Branch
                            </label>
                            <select id="branch_id" name="branch_id" class="input-sm" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $bran)
                                    <option value="{{$bran->office_id}}">{{$bran->office_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="staff_central_id" id="staff" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" required
                                   disabled>
                        </div>
                        <div class="form-group row">
                            <label for="from_date_np" class="col-3 col-form-label"> From (Date)</label>
                            {{ Form::text('from_date_np', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Date From', 'readonly' ))  }}
                            <input type="hidden" id="from_date" name="from_date">
                        </div>

                        <div class="form-group row">
                            <label for="to_date_np" class="col-3 col-form-label">
                                To (Date)
                            </label>
                            {{ Form::text('to_date_np', null, array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Date To', 'readonly')) }}
                            <input type="hidden" id="to_date" name="to_date">
                        </div>


                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Show',array('class'=>'btn btn-success btn-lg'))}}
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
    <script>
        var staffs = new Array();
        $('#branch_id').change(function () {
            branch = $(this).val();
            $.ajax({
                url: '{{route('get-staff-by-branch')}}',
                type: 'post',
                data: {
                    'branch': branch,
                    '_token': '{{csrf_token()}}'
                },
                success: function (data) {
                    //console.log(data);
                    staffs = data;
                    console.log(staffs);
                    $('#staff_central_id').remove();
                    $('.removeit').remove();
                    $('#staff').after('   <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" required\n' +
                        '                                   >')
                    $('#staff_central_id').prop('disabled', false);
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
                                    '<div> Staff ID: ' + item.main_id + '</div>' +
                                    '<div> Father Name: ' + item.FName_Eng + '</div></div>';
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
                    console.log($('#staff').next().next().addClass('removeit'));
                }
            });
        });
    </script>

    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()));
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()));
            }
        });


    </script>
@endsection
