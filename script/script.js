const hamburger = document.getElementsByClassName('hamburger')[0]
const navbarlinks = document.getElementsByClassName('navbarlinks')[0]

hamburger.addEventListener('click', () => {
    navbarlinks.classList.toggle('active')
}
)

function LoginFirst() {
    alert("You must be logged in to upload files!");
}

function WarnUser() {
    const button = document.getElementById('Warn');
    if (confirm("Are you sure you want to empty trash?")) {
        button.setAttribute('name', 'deletefile');
        return true;
    }
    else {
        return false;
    }
}

function storage(){
    alert("That's the amount of storage left on the server");
}