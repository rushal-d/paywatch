@extends('layouts.default', ['crumbroute' => 'staff-payroll-detail'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'staff-payroll-detail-show'))  }}
    {{method_field('GET')}}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Staff Payroll Details</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="staff_central_id" class="col-3 col-form-label">
                                Staff Name
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" required>

                        </div>

                        <div class="form-group row">
                            <label for="fy_year" class="col-3 col-form-label">
                                Fiscal year (FY)
                            </label>
                            {{ Form::select('fy_id', $fiscal_years, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}
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

    </div>
    {{ Form::close()  }}
@endsection
@section('script')
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
                        '<div>Payroll Branch: ' + item.payroll_branch.office_name + '</div></div>';
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
    <script src="{{ asset('nepalidate/nepali.datepicker.v2.2.min.js')  }}"></script>
    <script>
        $('.nep-date').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 20,
            onChange: function (e) {
                $('#nep-date').next().val(BS2AD($('#nep-date').val()))
            }
        });
    </script>
@endsection
