@extends('layouts.default', ['crumbroute' => 'house-loan-diff-income-create'])
@section('title', $title)
@section('content')
    {{ Form::open(array('route' => 'house-loan-diff-income-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">House Loan Diff Income Information</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="house_loan_id" class="col-3 col-form-label">
                                House Loan Id
                            </label>
                            <p>{{$houseLoan->house_id}}</p>
                            <input type="hidden" id="house_loan_id" name="house_loan_id"
                                   value="{{$houseLoan->house_id}}">
                        </div>
                        <div class="form-group row">
                            <label for="trans_date" class="col-3 col-form-label">
                                Fiscal Year
                            </label>
                            <?php
                            $selectedFiscalYear = $currentFiscalYearId;
                            if (!empty(request('fiscal_year_id'))) {
                                    $selectedFiscalYear = request('fiscal_year_id');
                            }
                            ?>
                            {{ Form::select('fiscal_year_id', $fiscalYears,$selectedFiscalYear,  array('class' => 'form-control','id' => 'fiscal_year_id', 'required' => 'required',
                                ))  }}
                        </div>
                        <div class="form-group row">
                            <label for="loan_amount" class="col-3 col-form-label">
                                Difference Income Amount
                            </label>

                            {{ Form::number('diff_income', null, array('class' => 'form-control','id'=>'diff_income' , 'placeholder' => 'Loan  Amount',
                             'data-validation' => 'required',
                             'data-validation-error-msg' => 'Please enter a Difference Income'))  }}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        <p id="msg"></p>
                        {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg','id'=>'save'))}}
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
        $('#fiscal_year_id').on('ready change', function () {
            let $_this = $(this);
            $.ajax({
                url: '{{route('get-previous-house-loan-diff-income-by-filter')}}',
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    fiscal_year_id: $_this.val(),
                    house_loan_id: $('#house_loan_id').val()
                },
                success: function (response) {
                    if (response.status === true) {
                        $('#diff_income').val(response.data.diff_income);
                    } else {
                        $('#diff_income').val(0);
                    }
                }
            })
        });

        $(document).ready(function () {
            $.ajax({
                url: '{{route('get-previous-house-loan-diff-income-by-filter')}}',
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    fiscal_year_id: $('#fiscal_year_id').val(),
                    house_loan_id: $('#house_loan_id').val()
                },
                success: function (response) {
                    if (response.status === true) {
                        $('#diff_income').val(response.data.diff_income);
                    } else {
                        $('#diff_income').val(0);
                    }
                }
            })
        });
    </script>
@endsection
