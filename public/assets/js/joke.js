function get_joke_of_the_day() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           var response = JSON.parse(this.responseText);
           var jokeOfTheDayData = response.contents.jokes[0];
           var jokeTitle = jokeOfTheDayData.joke.title;
           var jokeContent = jokeOfTheDayData.joke.text;
           //document.getElementById("jokeTitle").innerHTML = jokeTitle;
           document.getElementById("jokeContent").innerHTML = jokeContent;
        }
    };
    xhttp.open("GET", "https://api.jokes.one/jod", true);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send();
}

get_joke_of_the_day();