@extends('layouts.default', ['crumbroute' => 'staff-cit-deduction-create'])
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

    {{ Form::open(array('route' => 'staff-cit-deduction-save'))  }}
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

                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Branch <span class="required-field">*</span>
                            </label>
                            {!! Form::select('branch_id', $branches , null, array( 'id'=>'branch_id', 'required') ) !!}
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
        let branch_id = $('#branch_id');

        $('#filter').on('click', function () {
            $('#form').children().remove();
                $.ajax({
                    url: '{{route('staff-cit-deduction-show-filter-view')}}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        month_id: month_id.val(),
                        fiscal_year_id: fiscal_year_id.val(),
                        branch_id: branch_id.val()
                    },
                    success: function (response) {
                        if (response.status === true)
                            $('#form').html(response.html);
                        else
                            alert(response.message);
                    }
                });

        });
    </script>
@endsection
