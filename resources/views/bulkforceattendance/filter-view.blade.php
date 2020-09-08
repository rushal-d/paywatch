<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="basic-info card">

            <form action="{{route('bulk-force-store')}}" id="bulk-force-update-form" method="POST">
                {{csrf_field()}}
                <div class="level">
                    <h5 class="card-header flex">Bulk Force Update</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-responsive" id="input-form-staff">
                        <thead>
                        <tr>
                            <th scope="col">Staff ID</th>
                            <th scope="col">Staff Name</th>
                            <th scope="col">Attendance Date</th>
                            <th scope="col">Punch In Time<input type="time" id="punchin_time_for_all_inputs"></th>
                            <th scope="col">Tiffin Out Time<input type="time" id="tiffinout_time_for_all_inputs"></th>
                            <th scope="col">Tiffin In Time<input type="time" id="tiffinin_time_for_all_inputs"></th>
                            <th scope="col">Personal Out Time<input type="time" id="personalout_time_for_all_inputs">
                            </th>
                            <th scope="col">Personal In Time<input type="time" id="personalin_time_for_all_inputs"></th>
                            <th scope="col">Lunch Out Time<input type="time" id="lunchout_time_for_all_inputs"></th>
                            <th scope="col">Lunch In Time<input type="time" id="lunchin_time_for_all_inputs"></th>
                            <th scope="col">Punch Out Time<input type="time" id="punchout_time_for_all_inputs"></th>
                            <th scope="col">Previous Remarks</th>
                            <th scope="col">Remarks</th>
                            <th scope="col">Remove</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($staffs as $staff)
                            <tr>
                                <th scope="row">{{$staff->main_id}}</th>

                                <th scope="row" class="staff-name">{{$staff->name_eng}}</th>

                                <td id="display-attendance-date"
                                    data-attendance-date-np="{{$from_date_np}}">{{$from_date_np}}</td>

                                <td class="punchin_datetime_td">
                                    <input type="time" name="staffs[{{$staff->id}}][punchin_datetime_np]"
                                           required="required"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->punchin_datetime ? date('H:i',strtotime($staff->fetchAttendances->first()->punchin_datetime)) : null}}"
                                           class="punchin_datetime_np">
                                </td>

                                <td class="tiffinout_datetime_td">
                                    <input type="time" name="staffs[{{$staff->id}}][tiffinout_datetime_np]"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->tiffinout_datetime ? date('H:i',strtotime($staff->fetchAttendances->first()->tiffinout_datetime)) : null}}"
                                           class="tiffinout_datetime_np">
                                </td>

                                <td class="tiffinin_datetime_td">
                                    <input type="time" name="staffs[{{$staff->id}}][tiffinin_datetime_np]"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->tiffinin_datetime? date('H:i',strtotime($staff->fetchAttendances->first()->tiffinin_datetime)) : null}}"
                                           class="tiffinin_datetime_np">
                                </td>

                                <td>
                                    <input type="time" name="staffs[{{$staff->id}}][personalout_datetime_np]"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->personalout_datetime ? date('H:i',strtotime($staff->fetchAttendances->first()->personalout_datetime)) : null}}"
                                           class="personalout_datetime_np">
                                </td>

                                <td>
                                    <input type="time" name="staffs[{{$staff->id}}][personalin_datetime_np]"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->personalin_datetime ? date('H:i',strtotime($staff->fetchAttendances->first()->personalin_datetime)) : null}}"
                                           class="personalin_datetime_np">
                                </td>

                                <td>
                                    <input type="time" name="staffs[{{$staff->id}}][lunchout_datetime_np]"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->lunchout_datetime ? date('H:i',strtotime($staff->fetchAttendances->first()->lunchout_datetime)) : null}}"
                                           class="lunchout_datetime_np">
                                </td>

                                <td>
                                    <input type="time" name="staffs[{{$staff->id}}][lunchin_datetime_np]"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->lunchin_datetime ? date('H:i',strtotime($staff->fetchAttendances->first()->lunchin_datetime)) : null}}"
                                           class="lunchin_datetime_np">
                                </td>

                                <td class="punchout_datetime_td">
                                    <input type="time" name="staffs[{{$staff->id}}][punchout_datetime_np]"
                                           value="{{$staff->fetchAttendances->first() && $staff->fetchAttendances->first()->punchout_datetime ? date('H:i',strtotime($staff->fetchAttendances->first()->punchout_datetime)) : null}}"
                                           class="punchout_datetime_np">
                                </td>

                                <td class="previous-remarks" style="font-size: 10px">
                                    {!!  $staff->fetchAttendances->first()->remarks ?? null !!}
                                </td>

                                <td class="remarks">
                                    <input type="text" name="staffs[{{$staff->id}}][remarks]"
                                           value=""
                                           class="remarks">
                                </td>

                                <td class="text-center">
                                    <a href="javascript:void(0)" class="remove-staff-button"><i
                                            class="fa fa-trash fa-2x"
                                            aria-hidden="true"></i></a>
                                </td>

                                <input type="hidden" name="staffs[{{$staff->id}}][attendance_date_np]"
                                       value="{{$from_date_np}}">

                                <input type="hidden" name="staffs[{{$staff->id}}][attendance_date_en]"
                                       value="{{$from_date}}">
                            </tr>
                        @endforeach
                        <input type="hidden" name="attendance_date_np" value="{{$from_date_np}}">
                        <input type="hidden" name="branch_id" value="{{$branch_id}}">
                        </tbody>
                    </table>
                </div>
                <div class="background-color-brown text-center">
                    <button class="btn btn-success" id="bulk-force-submit-button">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script>
    $(".btn-success").click(function (e) {
        var selected_attendance_date = $("#display-attendance-date").data('attendance-date-np');
        $('.punchin_datetime_np').each(function () {
            if ($(this).val()) {
                var punchin_datetime_value = $(this).val();
                var punchout_datetime_value = $(this).parent().siblings('.punchout_datetime_td').find('.punchout_datetime_np').val();
                var tiffinout_datetime_value = $(this).parent().siblings('.tiffinout_datetime_td').find('.tiffinout_datetime_np').val();
                var tiffinin_datetime_value = $(this).parent().siblings('.tiffinin_datetime_td').find('.tiffinin_datetime_np').val();

                var current_date = '{{date('Y-m-d H:i:s')}}';

                var selected_attendance_punchout_datetime = BS2AD(selected_attendance_date) + ' ' + punchout_datetime_value;
                var selected_attendance_punchin_datetime = BS2AD(selected_attendance_date) + ' ' + punchin_datetime_value;
                var selected_attendance_tiffinin_datetime = BS2AD(selected_attendance_date) + ' ' + tiffinin_datetime_value;
                var selected_attendance_tiffinout_datetime = BS2AD(selected_attendance_date) + ' ' + tiffinout_datetime_value;

                if ((selected_attendance_punchout_datetime >= current_date)
                    || (selected_attendance_punchin_datetime >= current_date)
                    || selected_attendance_tiffinin_datetime >= current_date
                    || selected_attendance_tiffinout_datetime >= current_date
                ) {
                    e.preventDefault();

                    $(this).siblings('p').remove();
                    $(this).parent().parent().css("color", "black");

                    if (selected_attendance_punchout_datetime >= current_date) {
                        $(this).parent().append("<p class='validation-punch-out'>Punchout date should be less than current date</p>");
                    }

                    if (selected_attendance_punchin_datetime !== '' && selected_attendance_punchin_datetime >= current_date) {
                        $(this).parent().append("<p class='validation-punch-in'>Punchin date should be less than current date</p>");
                    }

                    if (selected_attendance_tiffinin_datetime !== '' && selected_attendance_tiffinin_datetime >= current_date) {
                        $(this).parent().append("<p class='validation-tiffin-in'>Tiffin in date should be less than current date</p>");
                    }

                    if (selected_attendance_tiffinout_datetime !== '' && selected_attendance_tiffinout_datetime >= current_date) {
                        $(this).parent().append("<p class='validation-tiffin-out'>Tiffin out date should be less than current date</p>");
                    }

                    $(this).parent().parent().css("color", "red");
                } else {
                    $(this).siblings('p').remove();
                    $(this).parent().parent().css("color", "black");

                    if ((punchin_datetime_value >= punchout_datetime_value) || ((tiffinout_datetime_value >= tiffinin_datetime_value))) {
                        if (punchin_datetime_value !== '' && punchout_datetime_value !== '' && punchin_datetime_value >= punchout_datetime_value) {
                            e.preventDefault();
                            $(this).parent().append("<p class='validation-punch-in'>Punchout date should be greater than punchin date</p>");
                            $(this).parent().parent().css("color", "red");
                        }
                        if (tiffinin_datetime_value !== '' && tiffinout_datetime_value !== '' && (tiffinout_datetime_value >= tiffinin_datetime_value)) {
                            e.preventDefault();
                            $(this).parent().append("<p class='validation-tiffin-in'>Tiffin in date should be greater than tiffin out date</p>");
                            $(this).parent().parent().css("color", "red");
                        }
                    } else {
                        $(this).siblings('p').remove();
                        $(this).parent().parent().css("color", "black");
                    }

                    /*if (tiffinin_datetime_value !== '' && tiffinout_datetime_value !== '') {
                        if (tiffinout_datetime_value >= tiffinin_datetime_value) {
                            e.preventDefault();
                            $(this).parent().append("<p class='validation-tiffin-in'>Tiffin in date should be greater than tiffin out date</p>");
                            $(this).parent().parent().css("color", "red");
                        }
                    } else {
                        $(this).siblings('p').remove();
                        $(this).parent().parent().css("color", "black");
                    }

                    if (punchin_datetime_value !== '' && punchout_datetime_value !== '') {
                        if (punchin_datetime_value >= punchout_datetime_value) {
                            e.preventDefault();
                            $(this).parent().append("<p class='validation-punch-in'>Punchout date should be greater than punchin date</p>");
                            $(this).parent().parent().css("color", "red");
                        }
                    } else {
                        $(this).siblings('p').remove();
                        $(this).parent().parent().css("color", "black");
                    }*/
                }
            }
        });
    });


    $(".remove-staff-button").on('click', function () {
        var removed_staff_name = $(this).parent().parent().find('.staff-name').html().toLowerCase();
        var staff_names = $('input[name="staff_central_id[]"]:checked')
            .map(function (index, input_field) {
                // staff_names.filter(':contains(' + $(this).text()  + ')')
                var staff_name = this.nextSibling.nodeValue.toLowerCase(); // $(this).val()
                if (staff_name.indexOf(removed_staff_name) !== -1) {
                    input_field.checked = false;
                }
            }).get();

        $(this).parent().parent().remove();
        var number_of_staff_inputs_rows = $('#input-form-staff tr').length;
        if (number_of_staff_inputs_rows === 1) {
            $('#bulk-force-update-form').remove();
        }

    })
</script>

<script>
    $('#punchin_time_for_all_inputs').on('change', function (e) {
        $(".punchin_datetime_np").val(e.target.value);

    });
    $('#tiffinout_time_for_all_inputs').change(function (e) {
        $(".tiffinout_datetime_np").val(e.target.value);
    });
    $('#tiffinin_time_for_all_inputs').change(function (e) {
        $(".tiffinin_datetime_np").val(e.target.value);

    });
    $('#personalout_time_for_all_inputs').change(function (e) {
        $(".personalout_datetime_np").val(e.target.value);

    });
    $('#personalin_time_for_all_inputs').change(function (e) {
        $(".personalin_datetime_np").val(e.target.value);
    });
    $('#lunchout_time_for_all_inputs').change(function (e) {
        $(".lunchout_datetime_np").val(e.target.value);

    });
    $('#lunchin_time_for_all_inputs').change(function (e) {
        $(".lunchin_datetime_np").val(e.target.value);

    });
    $('#punchout_time_for_all_inputs').change(function (e) {
        $(".punchout_datetime_np").val(e.target.value);

    });
</script>

