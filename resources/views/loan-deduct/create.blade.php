@extends('layouts.default', ['crumbroute' => 'loan-deduct-create'])
@section('title', $title)
@section('style')
    <style>

        .loan_id {
            width: 72%;
        }

        .selectize-control.input-sm.single {
            width: 100%;
        }
    </style>
@endsection
@section('content')

    {{ Form::open(array('route' => 'loan-deduct-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">{{$title}}</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Fiscal Year<span class="required-field">*</span>
                            </label>
                            {!! Form::select('fiscal_year_id', $fiscalYears , $currentFiscalYearId ?? null,array('id'=>'fiscal_year_id', 'placeholder' => 'Fiscal Year', 'required') ) !!}

                        </div>
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Month<span class="required-field">*</span>
                            </label>
                            {!! Form::select('month_id', $months , null, array( 'id'=>'month_id', 'placeholder'=>'Select a Month', 'required') ) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        <p id="msg"></p>
                        {{ Form::button('Filter',array('class'=>'btn btn-success btn-lg','id'=>'filter'))}}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div id="form">

            </div>
        </div>
    </div>

    {{ Form::close()  }}
@endsection



@section('script')
    <script>
        let fiscal_year_id = $('#fiscal_year_id');
        let month_id = $('#month_id');

        $('#filter').on('click', function () {
            $('#form').children().remove();
            if (month_id.val() > 0 && fiscal_year_id.val() > 0) {
                $.ajax({
                    url: '{{route('loan-deduct-show-filter-view')}}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        month_id: month_id.val(),
                        fiscal_year_id: fiscal_year_id.val()
                    },
                    success: function (response) {
                        if (response.status == 'true')
                            $('#form').html(response.html);
                        else
                            $('#form').html();
                    }
                });
                /*$.post("<?php echo route("loan-deduct-show-filter-view")?>", {
                    _token: '{{ csrf_token() }}',
                    month_id: month_id.val(),
                    fiscal_year_id: fiscal_year_id.val()
                }).done(function (data) {
                    console.log(data);
                    if (data.status == 'true')
                        $('#form').html(data.html);
                    else
                        $('#form').html();
                });*/
            } else {
                alert('Please choose fiscal year and month');
                // $('#form').html('<h3 class="alert alert-success text-center">Please select a staff</h3>')
            }
        });
    </script>
@endsection
