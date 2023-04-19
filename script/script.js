//hamburgermenu
function hamburgermenu() {
    const hamburger = document.getElementsByClassName('hamburger')[0]; // put the hamburger into a non changable variable
    const navbarlinks = document.getElementsByClassName('navbarlinks')[0]; // put the hyperlinks into variables

    hamburger.addEventListener('click', () => { // When the hamburger is clicked
        navbarlinks.classList.toggle('active'); // add class active to the navbar links
    });
}

if (typeof document.getElementsByClassName('hamburger')[0] !== 'undefined') { // check if there is element called hamburger
    hamburgermenu(); // if so call function hamburgermenu
}

// Logo button
function LoginFirst() {
    alert("You must be logged in to upload files!"); // Show alert with message
}

// Confirmation for deleting all files in trash
function WarnUser() {
    const button = document.getElementById('Warn');
    if (confirm("Are you sure you want to empty trash?")) { // Make prompt to confirm deletion of every single file
        button.setAttribute('name', 'deletefile'); // Change the name of the form to match the PHP code
        return true; // Yes delete all
    }
    else {
        return false; // No cancel
    }
}

// amount of storage in navbar
function storage() {
    alert("That's the amount of storage left on the server"); // Show alert with message
}