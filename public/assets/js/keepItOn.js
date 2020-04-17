var kill_music = function () {
    $.ajax({
        url: "kill_music"
    });
};


var play_music = function () {
    $.ajax({
        url: "/play_music/" + "keepItOn.mp3"
    });
};


var ajax_call = function() {
    $.ajax({
        url: "check_alarm",
        success: function(result){
            if (result === "checked") {
                play_music();
                setTimeout(kill_music, 5000);
            }
        }
    });
};

var interval = 1000 * 60 * 13;

setInterval(ajax_call, interval);