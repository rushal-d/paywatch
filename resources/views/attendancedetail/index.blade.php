@extends('layouts.default', ['crumbroute' => 'payroll'])
@section('title', $title)

@section('style')
    <link href="{{ asset('assets/css/vex.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vex-theme-os.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('attendance-detail-payroll') }}"><i
                            class="fa fa-plus"></i> Add
                        New</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" id="delete-selected" href="javascript:void(0)"><i
                            class="fa fa-minus"></i> Delete Selected</a>
                </li>
                {{--<li class="nav-item">--}}
                {{--<button  type="button"  class="btn btn-success" href="#"><i--}}
                {{--class="fa fa-file-excel-o"></i> Import Excel</button>--}}
                {{--</li>--}}
                {{--<li class="nav-item">--}}
                {{--<button  type="button"  class="btn btn-info"><i--}}
                {{--class="fa fa-file-excel-o"></i> Export Excel</button>--}}
                {{--</li>--}}
            </ul>
        </div>

        <div class="search-box">
            <form method="GET" action="{{route('attendance-detail-search')}}">
                {{ csrf_field() }}
                <div class="card-text">
                    <div class="form-group row">
                        <div class="col-4">
                            <label for="branch_id" class="col-3 col-form-label">
                                Branch
                            </label>
                            <select id="branch_id" name="branch_id" class="input-sm">
                                <option value="">Select Branch</option>
                                @foreach($branch as $bran)
                                    {{--<option value="{{$bran->office_id}}">{{$bran->office_name}}</option>--}}

                                    <option @if(\Request::get('branch_id') == $bran->office_id ) selected
                                            @endif value="{{$bran->office_id}}">{{$bran->office_name}}</option>
                                @endforeach
                            </select>
                            {{--<select name="" id="">--}}
                            {{--@foreach($branch as $bran)--}}
                            {{--<option @if(\Request::get('branch_id') == $bran->office_id ) selected--}}
                            {{--@endif value="{{$bran->office_id}}">{{$bran->office_name}}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                        </div>
                        <div class="col-4">
                            <label for="fiscal_year" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            <select id="fiscal_year" name="fiscal_year" class="input-sm">
                                <option value="">Select Year</option>
                                @foreach($fiscalyear as $fiscal)
                                    {{--<option value="{{$fiscal->id}}">{{$fiscal->fiscal_code}}</option>--}}
                                    <option @if(\Request::get('fiscal_year') == $fiscal->id ) selected
                                            @endif value="{{$fiscal->id}}">{{$fiscal->fiscal_code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="month_id" class="col-form-label">
                                Salary Month
                            </label>
                            <select id="month_id" name="month_id" class="input-sm">
                                <option value="">Select Month</option>
                                <option value="1">Baishakh</option>
                                <option value="2">Jestha</option>
                                <option value="3">Asar</option>
                                <option value="4">Shrawan</option>
                                <option value="5">Bhadau</option>
                                <option value="6">Aswin</option>
                                <option value="7">Kartik</option>
                                <option value="8">Mansir</option>
                                <option value="9">Poush</option>
                                <option value="10">Magh</option>
                                <option value="11">Falgun</option>
                                <option value="12">Chaitra</option>
                            </select>
                        </div>

                    </div>
                    {{--  <div class="form-group row">
                          <div class="col-4">
                              <label for="branch_id" class="col-3 col-form-label">
                                  Status
                              </label>
                              <select name="" id="">
                                  <option value="">Slect Branch</option>
                                  <option value="1">option 1</option>
                                  <option value="2">option 1</option>
                                  <option value="3">option 1</option>
                                  <option value="4">option 1</option>
                              </select>
                          </div>

                          <div class="col-4">
                              <label for="branch_id" class="col-3 col-form-label">
                                  Gender
                              </label>
                              <select name="" id="">
                                  <option value="">Slect Branch</option>
                                  <option value="1">option 1</option>
                                  <option value="2">option 1</option>
                                  <option value="3">option 1</option>
                                  <option value="">option 1</option>
                              </select>
                          </div>
                          <div class="col-4">
                              <label for="branch_id" class="col-form-label">
                                  Salary Month
                              </label>
                              <select name="" id="">
                                  <option value="">Slect Branch</option>
                                  <option value="1">option 1</option>
                                  <option value="2">option 1</option>
                                  <option value="3">option 1</option>
                                  <option value=4"">option 1</option>
                              </select>
                          </div>

                      </div>--}}
                    <div class="form-group row ml-1 text-right">
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary btn-reset">Filter</button>
                            <button type="button" class="btn btn-outline-success btn-reset">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fa fa-align-justify"></i> All Attendance Details
            <span class="tag tag-pill tag-success pull-right">{{ $attendance->total() }}</span>
        </div>
        <br>
        <div class="card-block">
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr align="left">
                    <th><input type="checkbox" class="check-all"></th>
                    <th>Id</th>
                    <th>Salary Month</th>
                    <th>Fiscal Year</th>
                    <th>Branch</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Total Days</th>
                    <th>Total Public Holidays</th>
                    <th>Prepared By</th>
                    <th>Confirmed By</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                @foreach($attendance as $atten)
                    <tr>
                        <td><input type="checkbox" class="check-id" data-id="{{ $atten->id }}"></td>
                        <td>{{ $atten->id  }}</td>
                        {{--<td><a href="{{ route('educationedit',['id' => $atten->id]) }}">{{ $atten->edu_description  }}</a></td>--}}
                        <td>
                            {{--{{ $atten->staff_central_id}}--}}
                            {{--todo - need to check for not value present--}}
                            {{$month_names[$atten->salary_month]}}

                        </td>
                        <td>{{ $atten->fiscalyear->fiscal_code}}</td>
                        <td>{{ $atten->branch->office_name}}</td>
                        <td>{{ $atten->from_date_np}}</td>
                        <td>{{ $atten->to_date_np}}</td>
                        <td>{{ $atten->total_days}}</td>
                        <td>{{ $atten->total_public_holidays}}</td>
                        <td>{{ $users->where('id',$atten->prepared_by)->first()->name ?? ''}}</td>
                        <td>{{ $users->where('id',$atten->confirmed_by)->first()->name ?? ''}}</td>
                        <td>@if(empty($atten->confirmed_by)) Not Confirmed Yet @endif</td>

                        <td class="actions-col">
                            <div class="actions">
                                <a href="{{route('attendance-detail-show',$atten->id)}}">
                                    <button class="btn btn-outline-info btn-sm">Detail View</button>
                                </a>
                                @if(empty($atten->confirmed_by))
                                    <a class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $atten->id }}"
                                       href="javascript:void(0)"><i class="fa fa-remove"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>
            <div class="pagination-links">{{ $attendance->appends($_GET)->links()
	  		}}
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script>
        //responsive table
        $(function () {
            $('.table-all').stacktable();
        });
    </script>

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
        //check uncheck all
        $('.check-all').change(function () {
            console.log($(this).prop('checked'))
            var checked = $(this).prop('checked')
            if (checked) {
                $('.check-id').prop('checked', true).trigger('change');
            } else {
                $('.check-id').prop('checked', false).trigger('change');
            }
        });

        //set id in array ids
        var ids = [];
        //individual checkbox change -- on change push to ids array for selected checkbox
        $(document).on('change', '.check-id', function () {
            console.log('checked');
            var checked = $(this).prop('checked');
            if (checked) {
                //check if already in array
                if ((ids.indexOf($(this).data('id')) > -1)) {
                } else {
                    ids.push($(this).data('id'));
                }
            } else {
                ids.splice($.inArray($(this).data('id'), ids), 1);
            }
        });
    </script>

    <script>
        //deleted bulk selected
        $('#delete-selected').click(function (e) {
            e.preventDefault();
            //Check if checkbox is unchecked
            if (ids != '') {
                vex.dialog.confirm({
                    message: 'Are you sure you want to delete?',
                    callback: function (value) {
                        if (value) { //true if clicked on ok
                            $.ajax({
                                type: "POST",
                                url: '{{ route('attendance-detail-destroy-selected') }}',
                                data: {_token: '{{ csrf_token() }}', ids: ids},
                                // send Blob objects via XHR requests:
                                success: function (response) {
                                    if (response == 'Successfully Deleted') {
                                        toastr.success('Successfully Deleted')
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 600);
                                    } else {
                                        vex.dialog.alert(response)
                                    }
                                },
                                error: function (response) {
                                    vex.dialog.alert(response)
                                }
                            });
                        }
                    }
                });
            } else {
                vex.dialog.alert('Please first make selection form the list')
            }
        });
    </script>

    <script>
        //delete
        $('body').on('click', '.delete-btn', function () {
            $this = $(this)
            vex.dialog.confirm({
                message: 'Are you sure you want to delete?',
                callback: function (value) {
                    console.log('Callback value: ' + value + $this.data('id'));
                    if (value) { //true if clicked on ok
                        $.ajax({
                            type: "POST",
                            url: '{{ route('attendance-detail-destroy') }}',
                            data: {_token: '{{ csrf_token() }}', id: $this.data('id')},
                            // send Blob objects via XHR requests:
                            success: function (response) {
                                if (response == 'Successfully Deleted') {
                                    toastr.success('Successfully Deleted')
                                    $this.parent().parent().parent().remove();
                                } else {
                                    vex.dialog.alert(response)
                                }
                            },
                            error: function (response) {
                                vex.dialog.alert(response)
                            }
                        });
                    }
                }
            });
        });
    </script>
    <script>
        $('#records_per_page').change(function () {
            $('.search-form').submit();
        });
    </script>
@endsection
