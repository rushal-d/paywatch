/**
 * Nepali Date Change
 */
$('.nep-date').nepaliDatePicker({
    npdMonth: true,
    npdYear: true,
    npdYearCount: 20,
    onChange: function (e) {
        $('#from_date_np').val() ? $('#from_date').val(BS2AD($('#from_date_np').val())) : '';
        $('#to_date_np').val() ? $('#to_date').val(BS2AD($('#to_date_np').val())) : '';

        if(checkDatesAreSet()){
            setTotalHolidayDays();
        }
    }
});
$('#from_date_np').val() ? $('#from_date').val(BS2AD($('#from_date_np').val())) : '';
$('#to_date_np').val() ? $('#to_date').val(BS2AD($('#to_date_np').val())) : '';
/**
 * End of Nepali Date Change
 */

/**
 * Flat Picker Changer
 */
$('.date').flatpickr({
    dateFormat: "Y-m-d",
    disableMobile: "true",
    onChange: function () {
        $('#from_date').val() ? $('#from_date_np').val(AD2BS($('#from_date').val())) : '';
        $('#to_date').val() ? $('#to_date_np').val(AD2BS($('#to_date').val())) : '';
        if(checkDatesAreSet()){
            setTotalHolidayDays();
        }
    }
});

if(checkDatesAreSet()){
    setTotalHolidayDays();
}

function setTotalHolidayDays(){
    var to_date =new Date($('#to_date').val());
    var from_date =new Date($('#from_date').val());
    var total_holiday_days= parseInt((to_date - from_date) / (1000 * 60 * 60 * 24));
    $('#total_holiday_days').html(total_holiday_days + ' days');
}


function checkDatesAreSet(){
    return $('#from_date').val() && $('#to_date').val();
}

$('#from_date').val() ? $('#from_date_np').val(AD2BS($('#from_date').val())) : '';
$('#to_date').val() ? $('#to_date_np').val(AD2BS($('#to_date').val())) : '';
/**
 * End Flat Picker Changer
 */

$('#religion_toggle').on('change', function(){
    if($(this).prop('checked')==true){
        $('.religion').prop("checked",true)

    }
    else
    {
        $('.religion').prop("checked",false)
    }
});

$('#caste_toggle').on('change', function(){
    if($(this).prop('checked')==true){
        $('.caste').prop("checked",true)

    }
    else
    {
        $('.caste').prop("checked",false)
    }
});
