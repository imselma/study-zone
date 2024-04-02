$(document).ready(function() {

    var currentDate = new Date();

    // Format the current date as "DD.MM.YYYY"
    var formattedDate = currentDate.toLocaleDateString('en-GB');

    $("#currentDate").text("Today, " + formattedDate);
});