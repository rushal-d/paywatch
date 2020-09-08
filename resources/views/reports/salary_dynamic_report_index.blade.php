@extends('layouts.default', ['crumbroute' => 'salary-dynamic-report-index'])
@section('title', $title)

@section('style')
    <style>
        .staff_container {
            width: 72%;
        }

        .selectize-control.input-sm.single {
            width: 100%;
        }
    </style>
    <style>
        .visibility {
            display: block;
        }

        .non-visibility {
            display: none !important;
        }
    </style>

@endsection
@section('content')
    {{ Form::open(array('route' => 'dynamicReport','method'=>'GET'))  }}
    <div class="row">
        <div class="col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Filter Salary Dynamic Report Index</h5>
                <div class="card-block">
                    <div class="card-text">
                        @if(!auth()->user()->hasRole('Employee'))
                            <div class="form-group row">
                                <label for="title" class="col-3 col-form-label">
                                    Branch<span class="required-field"> *</span>
                                </label>
                                {!! Form::select('branch_id', $branches , null,array('id'=>'branch_id','required'=>'required',
                                         'data-validation-error-msg' => 'Please Select Branch') ) !!}
                            </div>
                        @else
                            {!! Form::hidden('branch_id', auth()->user()->branch_id) !!}
                        @endif

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Year
                            </label>
                            {!! Form::select('fiscal_year_id', $fiscal_years , $current_fiscal_year_id ?? null,array('id'=>'fiscal_year_id', 'placeholder' => 'Fiscal Year') ) !!}
                        </div>

                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Select Month
                            </label>
                            {!! Form::select('month_id', $months , $currentNepaliDateMonth, array( 'id'=>'month_id') ) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="background-color-brown">
                <div class="level">
                    <div class="flex">
                        <button class="btn btn-primary btn-sm" id="check-all" type="button">Check All</button>
                        <button class="btn btn-danger btn-sm" id="uncheck-all" type="button">Uncheck All</button>
                    </div>
                </div>
            </div>
            {{--                </div>--}}
            <div class="card-block">
                <div class="form-group">

                    <input type="text" class="form-control" placeholder="Enter a desired column"
                           id="column-name-filter">
                </div>

                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <a href="#" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                   aria-expanded="true" aria-controls="collapseOne">
                                    Attendance Information:
                                </a>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                             data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @foreach($attendanceInformationClass as $className => $optionName)
                                                @php $increment++ @endphp
                                                <span class="visibility">
                                        <input type="checkbox" id="class-name"
                                               value="{{$className}}"
                                               data-class-id="{{$className}}"
                                               name="classes[]" multiple="multiple">{{$optionName}}
                                    </span>
                                                @if($increment==$breakCount)
                                        </div>
                                        <div class="col-md-4">
                                            @php $increment=1; @endphp
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                   data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Leaves:
                                </a>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @foreach($leavesClass as $className => $optionName)
                                                @php $increment++ @endphp
                                                <span class="visibility">
                                        <input type="checkbox" id="class-name"
                                               value="{{$className}}"
                                               data-class-id="{{$className}}"
                                               name="classes[]" multiple="multiple">{{$optionName}}
                                    </span>
                                                @if($increment==$breakCount)
                                        </div>
                                        <div class="col-md-4">
                                            @php $increment=1; @endphp
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                   data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Payable Parameters:
                                </a>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                             data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @foreach($payableParameters as $className => $optionName)
                                                @php $increment++ @endphp
                                                <span class="visibility">
                                        <input type="checkbox" id="class-name"
                                               value="{{$className}}"
                                               data-class-id="{{$className}}"
                                               name="classes[]" multiple="multiple">{{$optionName}}
                                    </span>
                                                @if($increment==$breakCount)
                                        </div>
                                        <div class="col-md-4">
                                            @php $increment=1; @endphp
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                                <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                   data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Bank Information:
                                </a>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @foreach($bankInformationClass as $className => $optionName)
                                                @php $increment++ @endphp
                                                <span class="visibility">
                                        <input type="checkbox" id="class-name"
                                               value="{{$className}}"
                                               data-class-id="{{$className}}"
                                               name="classes[]" multiple="multiple">{{$optionName}}
                                    </span>
                                                @if($increment==$breakCount)
                                        </div>
                                        <div class="col-md-4">
                                            @php $increment=1; @endphp
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h5 class="mb-0">
                                <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                   data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    Statements:
                                </a>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-text">
                                    <div class="row">
                                        <div class="col-md-4">
                                            @foreach($statementClass as $className => $optionName)
                                                @php $increment++ @endphp
                                                <span class="visibility">
                                        <input type="checkbox" id="class-name"
                                               value="{{$className}}"
                                               data-class-id="{{$className}}"
                                               name="classes[]" multiple="multiple">{{$optionName}}
                                    </span>
                                                @if($increment==$breakCount)
                                        </div>
                                        <div class="col-md-4">
                                            @php $increment=1; @endphp
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-success btn-sm" id="filter" type="submit">Filter</button>
        </div>
    </div>
    {{ Form::close()  }}
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
        $('#check-all').click(function () {
            $('.visibility input[type=checkbox]').prop('checked', true);
        });

        $('#uncheck-all').click(function () {
            $('.visibility input[type=checkbox]').prop('checked', false);
        });
    </script>

    <script>
        $("#column-name-filter").on('keyup', function () {
            var staff_name_filter_input = this.value.toLowerCase();
            var staff_names = $('input[name="classes[]"]').map(function (index, input_field) {
                var staff_name = this.nextSibling.nodeValue.toLowerCase().trim();
                var staff_main_id = this.getAttribute('data-class-id').trim();
                if (staff_name.indexOf(staff_name_filter_input) === -1) {
                    input_field.parentNode.className = 'non-visibility';
                } else {
                    input_field.parentNode.className = 'visibility';
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>

@endsection
