@extends('layouts.default', ['crumbroute' => 'taxstatement'])
@section('title', $title)
@section('content')
    <form action="{{ route('taxstatement-personal') }}" method="get" enctype="multipart/form-data">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                {{-- Basic Info --}}
                <div class="basic-info card">
                    <h5 class="card-header">{{ $title }} Reports</h5>
                    <div class="card-block">
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="branch_id" class="col-3 col-form-label">
                                    Name of Staff
                                </label>
                                <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                                       required>
                            </div>
                            <div class="form-group row">
                                <label for="fiscal_year" class="col-3 col-form-label">
                                    Fiscal Year
                                </label>
                                <select id="fiscal_year" name="fiscal_year" class="input-sm" required>
                                    @foreach($fiscalyear as $fiscal)
                                        <option value="{{$fiscal->id}}" {{$fiscal->id == $fiscal_year->id ? 'selected' : ''}}>{{$fiscal->fiscal_code}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label for="from" class="col-3 col-form-label">
                                    From
                                </label>
                                <select id="from_month" name="from_month" class="input-sm" required>
                                    <option value="">Select One</option>
                                    <option value="4">Shrawan</option>
                                    <option value="5">Bhadau</option>
                                    <option value="6">Aswin</option>
                                    <option value="7">Kartik</option>
                                    <option value="8">Mansir</option>
                                    <option value="9">Poush</option>
                                    <option value="10">Magh</option>
                                    <option value="11">Falgun</option>
                                    <option value="12">Chaitra</option>
                                    <option value="1">Baishakh</option>
                                    <option value="2">Jestha</option>
                                    <option value="3">Asar</option>
                                </select>
                            </div>

                            <div class="form-group row">
                                <label for="from" class="col-3 col-form-label">
                                    To
                                </label>
                                <select id="to_month" name="to_month" class="input-sm" required>
                                    <option value="">Select One</option>
                                    <option value="4">Shrawan</option>
                                    <option value="5">Bhadau</option>
                                    <option value="6">Aswin</option>
                                    <option value="7">Kartik</option>
                                    <option value="8">Mansir</option>
                                    <option value="9">Poush</option>
                                    <option value="10">Magh</option>
                                    <option value="11">Falgun</option>
                                    <option value="12">Chaitra</option>
                                    <option value="1">Baishakh</option>
                                    <option value="2">Jestha</option>
                                    <option value="3">Asar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{--  Save --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="text-right form-control">
                                {{ Form::submit('Submit',array('class'=>'btn btn-success'))}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            {{--{{ Form::close()  }}--}}
        </div>
    </form>
    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <span class="nav-link"> <i class="fa fa-align-justify"></i> Tax Statement</span>
                </li>

            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'taxstatement', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('Fiscal Year', 'Fiscal Year') }}
                    {{ Form::select('fiscal_year',$fiscalyear->pluck('fiscal_code','id'), null, array('class' => '', 'placeholder' => 'Fiscal Year'))}}
                </div>

                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('Branch', 'Branch') }}
                    <select id="branch_id" name="branch_id" class="input-sm" required>
                        <option value="all">All Branches</option>
                        @foreach($branch as $office_id => $bran)
                            <option value="{{$office_id}}">{{$bran}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('From', 'From') }}
                    <select id="month" name="from_month" class="input-sm" required>
                        <option value="">Select One</option>
                        <option value="4">Shrawan</option>
                        <option value="5">Bhadau</option>
                        <option value="6">Aswin</option>
                        <option value="7">Kartik</option>
                        <option value="8">Mansir</option>
                        <option value="9">Poush</option>
                        <option value="10">Magh</option>
                        <option value="11">Falgun</option>
                        <option value="12">Chaitra</option>
                        <option value="1">Baishakh</option>
                        <option value="2">Jestha</option>
                        <option value="3">Asar</option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('To', 'To') }}
                    <select id="month" name="to_month" class="input-sm" required>
                        <option value="">Select One</option>
                        <option value="4">Shrawan</option>
                        <option value="5">Bhadau</option>
                        <option value="6">Aswin</option>
                        <option value="7">Kartik</option>
                        <option value="8">Mansir</option>
                        <option value="9">Poush</option>
                        <option value="10">Magh</option>
                        <option value="11">Falgun</option>
                        <option value="12">Chaitra</option>
                        <option value="1">Baishakh</option>
                        <option value="2">Jestha</option>
                        <option value="3">Asar</option>
                    </select>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                    {{ Form::submit('Filter',array('class'=>'btn btn-outline-success btn-reset'))}}
                    <a class="btn btn-outline-success btn-reset" href="{{ route('taxstatement')}}"><i
                            class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>
    <div class="card">

        <div class="card-block">
            <p class="text-right"><input type="button" onclick="printDiv('printableArea')" value="Print"
                                         class="btn btn-primary btn-sm" id="print">
            </p>
            <div id="printableArea" class="row">
                <div class="text-center col-md-12">
                    <h5>Tax Statement</h5>
                </div>
                @if(empty($filter_data))
                    @role('Administrator')
                    <div class="text-center col-md-12">
                        Data from all branches in current fiscal year
                    </div>
                    @endrole
                @else
                    <table align="center" width="80%" cellspacing="0">
                        <tr>
                            <td><b>Fiscal Year : </b>{{$filter_data['fiscal_year']}}</td>
                            <td><b>Branch : </b>{{$filter_data['branch']}}</td>
                            <td><b>Month From : </b> {{$month_names[$filter_data['from']]}}</td>
                            <td><b>Month To : </b>{{$month_names[$filter_data['to']]}}</td>
                        </tr>
                    </table>
                @endif

                <table border="1px" width="80%" align="center" class="text-center">
                    <thead>
                    <th colspan="7">Fiscal Year-{{$fiscal_year->fiscal_code}}</th>
                    </thead>
                    <thead class="th-size text-center">
                    <th width="10%">S.No.</th>
                    <th width="20%">Month</th>
                    <th width="15%">No. of Staff Paid</th>
                    <th>Tax Amount</th>
                    <th>Total</th>
                    </thead>
                    @foreach($month_names as $month=>$month_name)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$month_names[$month]}}</td>
                            <td>{{$details['no_of_staff_paid'][$month] ?? '-'}}</td>
                            <td>{{$details['amount'][$month] ?? '-'}}</td>
                            <td>{{$details['total'][$month] ?? '-'}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

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
                        '<div> Main ID: ' + item.main_id + '</div>' +
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


    </script>


@endsection
