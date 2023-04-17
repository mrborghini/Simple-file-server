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