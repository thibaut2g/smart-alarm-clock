(function() {
    var httpRequest;

    var alarmSettingForm = document.getElementsByClassName("alarmSettingForm");

    for (let item of alarmSettingForm) {
        item.addEventListener('submit', function(event){
            event.preventDefault();
            makeRequest(item)
        });
    }


    function makeRequest(item) {

        httpRequest = new XMLHttpRequest();

        var day = item.querySelector("#alarm_settings_day").value;
        var time = item.querySelector("#alarm_settings_time").value;
        var music = item.querySelector("#alarm_settings_music").value;
        console.log(time);

        var params = 'day=' + day + '&time=' + time + '&music=' + music;

        httpRequest.open('POST', '/save');
        httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        httpRequest.onreadystatechange = alertContents;
        httpRequest.send(params);
    }

    function alertContents() {
        if (httpRequest.readyState === XMLHttpRequest.DONE) {
            if (httpRequest.status !== 200) {
                M.toast({html: 'Erreur web.. non enregistré 😓'})
            } else {
                if(httpRequest.responseText == "success") {
                    M.toast({html: 'Enregistré ! 😉'})
                } else {
                    M.toast({html: 'Erreur programme.. non enregistré 😓'})
                }
            }
        }
    }
})();