$(function () {
    $('#play').on('click', function () {
        $.ajax({
            url: "play_music",
            success: function(result){
                $("#musicName").html(result);
            }});
    });
});

$(function () {
    $('#quit').on('click', function () {
        $.ajax({
            url: "kill_music",
            success: function(result){
                $("#musicName").html(result);
            }});
    });
});

$(function () {
    $('#manage').on('click', function () {
        $('#pt-page-1').removeClass("pt-page-scaleUp pt-page-current");
        $('#pt-page-1').addClass("pt-page-scaleDown");
        $('#pt-page-2').addClass("pt-page-scaleUp pt-page-current");
    });
});

$(function () {
    $('#return').on('click', function () {
        $('#pt-page-2').removeClass("pt-page-scaleUp pt-page-current");
        $('#pt-page-1').removeClass("pt-page-scaleDown");
        $('#pt-page-2').addClass("pt-page-scaleDown");
        $('#pt-page-1').addClass("pt-page-scaleUp pt-page-current");
    });
});


$(document).ready(function(){
    $('select').formSelect();
});

$(document).ready(function(){
    $('.timepicker').timepicker({
        defaultTime: '07:30',
        twelveHour: false
    });
});