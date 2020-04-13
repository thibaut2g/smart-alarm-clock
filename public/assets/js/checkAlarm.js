$(function () {
    $('#alarmButton').on('click', function () {
        $.ajax({
            url: "kill_music",
            success: function(result){
                $('#alarmButton').addClass("hiddendiv");
                $("#musicName").html(result);
            }
        });
    });
});


var ajax_call = function() {
    $.ajax({
        url: "check_alarm",
        success: function(result){
            if (result !== "checked") {
                $('#alarmButton').removeClass("hiddendiv");
                $('#alarmButton p').html(result);
                $("#musicName").html(result);
            }
        }
    });
};

var interval = 1000 * 60;

setInterval(ajax_call, interval);