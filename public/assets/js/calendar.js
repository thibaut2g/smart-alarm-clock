function get_calendar_events() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            var resultContent = '<div class="row">';

            for (var key in response) {
                resultContent += '<div class="col s4"><p class="chip">' + key + '</p>';
                for (var value in response[key])  {
                    resultContent += '<p><b>' + response[key][value]["time"] + '</b> ' + response[key][value]["summary"] + '</p>'
                }
                resultContent += '</div>';
            }
            resultContent += '</div>';
            document.getElementById("calendarContent").innerHTML = resultContent
            ;
        }
    };
    xhttp.open("GET", "/get-calendar-events", true);
    xhttp.send();
}

get_calendar_events();