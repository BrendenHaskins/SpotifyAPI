window.addEventListener("DOMContentLoaded", (event) => {
    const element = document.getElementById("guess");
    const resultsQuerySelector = document.getElementById('result');
    element.addEventListener('focus', function() {
        resultsQuerySelector.style.display = "block";
    });
    element.addEventListener('focusout', function() {
        resultsQuerySelector.style.display = "none";
    });
});

function autocompleteMatch(userInput) {
    if (userInput === '') {
        return [];
    }
    var regInput = new RegExp(userInput.toLowerCase())
    return artistArray.filter(function(artist) {
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
    let size = 5;
    if (artists.length < size) {
        size = artists.length;
    }
    for (i = 0; i < size; i++) {
        list += '<li>' + capitalizeFirstLetter(artists[i]) + '</li>';
    }
    res.innerHTML = '<ul>' + list + '</ul>';
}

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