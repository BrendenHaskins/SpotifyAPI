//on load, run these
window.addEventListener("DOMContentLoaded", (event) => {
    const element = document.getElementById("guess");
    const resultsQuerySelector = document.getElementById('result');
    //if guess query gets selected, un-hide the suggestions
    element.addEventListener('focus', function() {
        resultsQuerySelector.style.display = "block";
    });
    //if guess query gets un-selected after 500 milliseconds, hide the suggestions
    element.addEventListener('focusout', function() {
        setTimeout(() => {
            resultsQuerySelector.style.display = "none";
        }, 500);

    });
});

function autocompleteMatch(userInput) {
    if (userInput === '') {
        return [];
    }
    //collect user input and put into a regexp
    var regInput = new RegExp(userInput.toLowerCase())
    return artistArray.filter(function(artist) {
        //is it the same?
        artist = artist.toLowerCase();
        if (artist.match(regInput)) {
            return artist;
        }
    });
}

function showResults(val) {
    let res = document.getElementById("result");
    res.innerHTML = '';
    let list = '';
    let artists = autocompleteMatch(val);
    //only show 5 artists
    let size = 5;
    if (artists.length < size) {
        size = artists.length;
    }
    //add elements to a NEW <li>
    for (i = 0; i < size; i++) {
        list += '<li> <button type="submit">' + capitalizeFirstLetter(artists[i]) + '</button> </li>';
    }
    //add the list to a <ul> in results
    res.innerHTML = '<ul>' + list + '</ul>';
}

//probably a useless function, but is there for "bob marley" or something un-capitalized (was useful in testing)
function capitalizeFirstLetter(string) {
    let words = string.split(" ");
    let word = "";
    string = "";
    for (let i = 0; i < words.length; i++) {
        word = words[i];
        string += word.charAt(0).toUpperCase() + word.slice(1);
        if (i !== words.length-1) {
            string += " ";
        }
    }
    return string;
}