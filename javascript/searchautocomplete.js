//on load, run these
window.addEventListener("DOMContentLoaded", (event) => {
    const element = document.getElementById("guess");
    const resultsQuerySelector = document.getElementById('result');

    // if window is clicked, hide the results
    document.body.addEventListener('click', function(){
            resultsQuerySelector.style.display = "none";
    }, false);

    //else open it
    element.addEventListener('click',function() {
        setTimeout(() => {
            resultsQuerySelector.style.display = "block";
    }, 1);
    }, true);

    //if results are right-clicked, still shows
    resultsQuerySelector.addEventListener('click',function() {
        setTimeout(() => {
            resultsQuerySelector.style.display = "block";
    }, 1);
    }, true);
});

/**
 * checks if the autocomplete matches the selected input
 *
 * @param userInput the text the user types
 */
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

/**
 * Shows the results of the current input in the text field
 *
 * @param val value in box
 */
function showResults(val) {
    let res = document.getElementById("result");
    res.innerHTML = '';
    let list = '';
    let safeArtist = '';
    let artists = autocompleteMatch(val);
    //only show 5 artists
    let size = 5;
    if (artists.length < size) {
        size = artists.length;
    }
    //add elements to a NEW <li>
    for (let i = 0; i < size; i++) {
        // some niche logic for styling purposes
        let color = i % 2 === 0 ? 'rgb(52, 52, 52)' : 'rgb(42, 42, 42)'
        let rounding = () => {
            if (i === 0) {return 'rounded-top'}
            else if (i === size - 1) {return 'rounded-bottom'}
            else return "";
        }

        safeArtist = (artists[i]).replace(/"/g, '&quot;');
        list += '<li class=" ' + rounding() + '" style="background-color: ' + color + '"> <button onclick="enterValue(\'' + capitalizeFirstLetter(safeArtist)
            + '\')">' + capitalizeFirstLetter(artists[i]) + '</button> </li>';
    }
    //add the list to a <ul> in results
    res.innerHTML = '<ul>' + list + '</ul>';
}

/**
 * Automatically inserts the value when clicked
 *
 * @param value item clicked
 */
function enterValue(value) {
    document.getElementById("guess").value = value;
    document.getElementById("guessingForm").submit();
}

/**
 * Capitalizes the first letter of every word
 * probably a useless function, but is there for "bob marley" or something un-capitalized (was useful in testing)
 *
 * @param string input
 * @returns {string} Capitalized Input
 */
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