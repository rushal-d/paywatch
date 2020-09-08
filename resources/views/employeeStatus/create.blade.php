@extends('layouts.default', ['crumbroute' => 'staff-status-create'])
@section('title', $title)
@section('content')

    {{ Form::open(array('route' => 'staff-status-save'))  }}
    <div class="row">
        <div class="col-md-7 col-sm-12">
            {{-- Basic Info --}}
            <div class="basic-info card">
                <h5 class="card-header">Add Staff Status</h5>
                <div class="card-block">
                    <div class="card-text">
                        <div class="form-group row">
                            <label for="title" class="col-3 col-form-label">
                                Staff Name<span class="required-field">*</span>
                            </label>
                            <input type="text" id="staff_central_id" name="staff_central_id" class="input-sm" required>
                        </div>
                        <div class="form-group row">
                            <label for="date_from_np" class="col-3 col-form-label">Date From<span class="required-field">*</span></label>
                            {{ Form::text('date_from_np', null, array('class' => 'form-control nep-date','required' => 'required','id'=>'nep-date1' , 'placeholder' => 'Date From', 'readonly')) }}
                            <input type="hidden" id="date_from" name="date_from">
                        </div>
                        <div class="form-group row">
                            <label for="date_to_np" class="col-3 col-form-label">Date To</label>
                            {{ Form::text('date_to_np', null, array('class' => 'form-control nep-date','id'=>'nep-date2' , 'placeholder' => 'Date To', 'readonly')) }}
                            <input type="hidden" id="date_to" name="date_to">
                        </div>
                        <div class="form-group row">
                            <label for="date_to_np" class="col-3 col-form-label">Status<span class="required-field">*</span></label>
                            {{ Form::select('status', $status, null, array('placeholder' => 'Select One...', 'required' => 'required'))  }}

                        </div>
                    </div>
                </div>
            </div>

            {{--  Save --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right form-control">
                        {{ Form::submit('Save',array('class'=>'btn btn-success btn-lg'))}}
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{ Form::close()  }}
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
                        $('#holiday_days').val(diff_days);
                    }
                    else {
                        $('#holiday_days').val(0);
                        toastr.error('Please check start holiday date from and to! Holiday must be at least one day!', 'Error!')
                    }
                }
            }
        });

        function parseDate(str) {
            var mdy = str.split('-');
            return new Date(mdy[0], mdy[1] - 1, mdy[2]);
        }

        function daydiff(first, second) {
            return Math.round((second - first) / (1000 * 60 * 60 * 24));
        }

        //selectize get staff details
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
                        '<div> Branch ID: ' + item.main_id + '</div>' +
                        '<div> Branch: ' + item.branch.office_name + '</div>' +
                        '<div> CID: ' + item.staff_central_id + '</div>' +
                        '</div>';
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
