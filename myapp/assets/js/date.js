$(document).ready(function() {

    var currentDate = new Date();

    //"DD.MM.YYYY"
    var formattedDate = currentDate.toLocaleDateString('en-GB');

    $("#currentDate").text(formattedDate);
});