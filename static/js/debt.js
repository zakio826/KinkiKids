
document.addEventListener('DOMContentLoaded', function () {
    var currentDateElement = document.getElementById('currentDate');
    var currentDate = new Date();

    var formattedDate = currentDate.getFullYear() + '/' + (currentDate.getMonth() + 1) + '/' + currentDate.getDate();

    currentDateElement.textContent = formattedDate;
});