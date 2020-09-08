@extends('layouts.default', ['crumbroute' => 'bankstatement'])
@section('title', $title)
@section('content')


    <form action="{{ route('bankstatement') }}" method="get" enctype="multipart/form-data">

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
                                <select id="branch_id" name="branch_id" class="input-sm" required>
                                    @foreach($branch as $office_id => $bran)
                                        <option value="{{$office_id}}">{{$bran}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="fiscal_year" class="col-3 col-form-label">
                                    Fiscal Year
                                </label>
                                <select id="fiscal_year" name="fiscal_year" class="input-sm" required>
                                    @foreach($fiscalyear as $fiscal)
                                        <option
                                            value="{{$fiscal->id}}" {{$fiscal->id == $currentFiscalYear->id ? 'selected' : ''}}>
                                            {{$fiscal->fiscal_code}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row">
                                <label for="month" class="col-3 col-form-label payroll_label">
                                    Payroll
                                </label>
                                {!! Form::select('payroll_id',$payrolls,null,['class'=>'form-control payroll_id','placeholder'=>'Select Payroll ...']) !!}
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
        <div class="card-header">
            <i class="fa fa-align-justify"></i>
            <span>{{ $title }}</span>
        </div>
        <div class="card-block">
            <div style="float: right;">
                <form action="{{route('bankstatement-export')}}" method="get">
                    <input type="hidden" name="branch_id" value="{{$inputs['branch_id'] ?? ''}}">
                    <input type="hidden" name="fiscal_year" value="{{$inputs['fiscal_year'] ?? ''}}">
                    <input type="hidden" name="month" value="{{$inputs['month'] ?? ''}}">
                    <input type="hidden" name="bank" value="{{$inputs['bank'] ?? ''}}">
                    <button type="submit" class="btn btn-info btn-sm">Export</button>
                </form>

            </div>
            <table class="table table-responsive table-striped table-hover table-all" width="100%" cellspacing="0">
                <tbody>
                <tr>
                    <td>S.No.</td>
                    <td>Staff_Name</td>
                    <td>Central Staff No. (CID)</td>
                    <td>Payroll Month</td>
                    <td>Branch Staff ID</td>
                    <td>Bank</td>
                    <td>MainCode (Account No.)</td>
                    <td>CyCode</td>
                    <td>BrCode</td>
                    <td>TranTyp</td>
                    <td>Total Salary Payment</td>
                    <td>Remarks</td>
                </tr>

                @if($status)
                    @foreach($details as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $detail->staff->name_eng ?? '' }}</td>
                            <td>{{ $detail->staff->staff_central_id ?? '' }}</td>
                            <td>{{ $detail->payroll->fiscalyear->fiscal_code ?? ''}}
                                :-{{$detail->payroll->salary_month ?? '' }}</td>
                            <td>{{ $detail->branch->office_name ?? '' }}</td>
                            <td>{{ $detail->bank->bank_name ?? '' }}</td>
                            <td>{{ $detail->acc_no   }}</td>
                            <td>{{ 'NPR' }}</td>
                            <td>{{ $detail->brcode }}</td>
                            <td>{{ $detail->trans_type }}</td>
                            <td>{{ $detail->total_payment  }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                @endif

                </tbody>

            </table>
            @if($status)
                <div class="pagination-links">{{ $details->appends($_GET)->links()
	  		}}
                </div>
            @endif
        </div>
    </div>

@endsection

@section('script')
    <script>
        $('#fiscal_year,#branch_id').change(function () {

            $('.payroll_id').remove();
            $('.payroll_label').after('<input type="text" id="payroll_id" placeholder="Select Payroll" name="payroll_id" class="payroll_id form-control">');

            $.ajax({
                url: '{{route('get-payroll-name')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    'fiscal_year_id': $('#fiscal_year').val(),
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
                        render: {

                        },
                        load: function (query, callback) {

                        }
                    });
                }
            });
        })
    </script>
@endsection
