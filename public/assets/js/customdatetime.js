function getDiffHoursFromTwoTime(lesserDate, greaterDate) {
    lesserDate = lesserDate.split(":");
    greaterDate = greaterDate.split(":");
    var lesserDateFormat = new Date(0, 0, 0, lesserDate[0], lesserDate[1], 0);
    var greaterDateFormat = new Date(0, 0, 0, greaterDate[0], greaterDate[1], 0);
    var diff = greaterDateFormat.getTime() - lesserDateFormat.getTime();

    return Math.floor(diff / 1000 / 60 / 60);
}

function getDiffMinutesFromTwoTime(lesserDate, greaterDate) {
    lesserDate = lesserDate.split(":");
    greaterDate = greaterDate.split(":");
    var lesserDateFormat = new Date(0, 0, 0, lesserDate[0], lesserDate[1], 0);
    var greaterDateFormat = new Date(0, 0, 0, greaterDate[0], greaterDate[1], 0);
    var diff = greaterDateFormat.getTime() - lesserDateFormat.getTime();

    return (diff / 1000 / 60) % 60;
}

//TODO:: getHoursFromMinutes for Total Work Hour
function getHoursFromMinutes(minute_date) {
    return Math.floor(minute_date / 60);
}

function getRemainingMinutesSubtractingForHours(minute) {
    return Math.floor(parseInt(minute) % 60);
}

function getMinutesFromHours(hour_date) {
    return Math.floor(parseInt(hour_date) * 60);
}
