@extends('layouts.default', ['crumbroute' => 'sundry-report'])
@section('title', $title)
@section('content')

    <div class="card">
        <div class="quick-actions">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <span class="nav-link"> <i class="fa fa-align-justify"></i> {{$title}}</span>
                </li>

            </ul>
        </div>

        <div class="search-box">
            {{ Form::open(array('route' => 'sundry-report', 'method'=> 'GET' ,'class' => 'search-form')) }}
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('staff', 'Staff Name') }}
                    <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm"
                           required>
                </div>
                {{-- <div class="col-md-3 col-sm-12 col-xs-12">
                     {{ Form::label('fiscal_year', 'Fiscal Year') }}
                     <select id="fiscal_year" name="fiscal_year" class="input-sm" required>
                         @foreach($fiscalyear as $fiscal)
                             <option value="{{$fiscal->id}}">{{$fiscal->fiscal_code}}</option>
                         @endforeach
                     </select>
                 </div>--}}
                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('fiscal_year', 'From') }}
                    {{ Form::text('from_date_np', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'From', 'readonly' ))  }}
                    <input type="hidden" id="from_date" name="from_date">
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    {{ Form::label('fiscal_year', 'To') }}
                    {{ Form::text('to_date_np', null, array('class' => 'form-control nep-date', 'required' => 'required', 'id'=>'nep-date2' , 'placeholder' => 'To', 'readonly')) }}
                    <input type="hidden" id="to_date" name="to_date">
                </div>

                <div class="col-md-1 col-sm-12 col-xs-12">
                    <button class="btn btn-outline-success btn-reset"> Filter</button>
                </div>
                <div class="col-md-1 col-sm-12 col-xs-12">
                    <a class="btn btn-outline-success btn-reset" href="{{ route('sundry-report')}}"><i
                                class="icon-refresh"></i> Reset</a>
                </div>
            </div>
            {{ Form::close()  }}
        </div>
    </div>

    <div class="card">

        <div class="card-block">
            <p class="text-right">  <input type="button" onclick="printDiv('printableArea')" value="Print"
                                           class="btn btn-primary btn-sm" id="print">
            </p>
            <div class="row" id="printableArea">
                <div class="col-md-12">
                    @if(!empty($staff_detail))
                        <p>Staff Name: {{$staff_detail['name']}}</p>
                        <p>From Date: {{$staff_detail['from']}}</p>
                        <p>To Date: {{$staff_detail['to']}}</p>
                    @else
                        <p>Sundry Details of Current Fiscal Year</p>
                    @endif

                    <div class="row">
                        <div class="col-md-6">Dr.</div>
                        <div class="col-md-6 text-right">Cr.</div>
                    </div>
                    <table class="table  table-bordered table-all" width="100%"
                           cellspacing="0">
                        <thead>
                        <th>Date</th>
                        <th>Descriptions</th>
                        <th>Dr Amount</th>
                        <th>Cr Amount</th>
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Descriptions</th>
                        <th>Dr Amount</th>
                        <th>Cr Amount</th>
                        <th>Balance</th>
                        </thead>
                        <tbody>

                        @for($i=0;$i<$count;$i++)
                            <tr>
                                <td>{{$debtors[$i]['date'] ?? ''}}</td>
                                <td>{{$debtors[$i]['transaction_type'] ?? ''}}</td>
                                <td>{{$debtors[$i]['dr_amount'] ?? ''}}</td>
                                <td>{{$debtors[$i]['cr_amount'] ?? ''}}</td>
                                <td>{{$debtors[$i]['balance'] ?? ''}}</td>
                                <td>{{$creditors[$i]['date'] ?? ''}}</td>
                                <td>{{$creditors[$i]['transaction_type'] ?? ''}}</td>
                                <td>{{$creditors[$i]['dr_amount'] ?? ''}}</td>
                                <td>{{$creditors[$i]['cr_amount'] ?? ''}}</td>
                                <td>{{$creditors[$i]['balance'] ?? ''}}</td>
                            </tr>
                        @endfor
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('script')
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>

    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date1').next().val(BS2AD($('#nep-date1').val()))
                $('#nep-date2').next().val(BS2AD($('#nep-date2').val()))

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
                    }
                    else {
                        $('#total_days').val(0);
                        toastr.error('Please check date from and to!', 'Error!')
                    }
                }
            }
        });

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
