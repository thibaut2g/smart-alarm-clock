var days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
var months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

(function () {
    function checkTime(i) {
        return (i < 10) ? "0" + i : i;
    }

    function startTime() {
        var today = new Date(),
            h = checkTime(today.getHours()),
            m = checkTime(today.getMinutes()),
            s = checkTime(today.getSeconds()),
            y = checkTime(today.getFullYear()),
            monthName = months[today.getMonth()],
            d = checkTime(today.getDate()),
            dayName = days[today.getDay()]
        ;
        document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
        document.getElementById('date').innerHTML = dayName + " " + d + " " + monthName + " " + y;
        setTimeout(function () {
            startTime()
        }, 500);
    }
    startTime();
})();