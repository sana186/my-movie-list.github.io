const signUp = e => {
    let username = document.getElementById('username').value;

    let formData = JSON.parse(localStorage.getItem('formData')) || [];

    let exist = formData.length &&
        JSON.parse(localStorage.getItem('formData')).some(data =>
            data.username.toLowerCase() == username.toLowerCase()
        );

    if(!exist){
        formData.push({ username });
        localStorage.setItem('formData', JSON.stringify(formData));
        document.querySelector('form').reset();
        document.getElementById('username').focus();
        alert("Account Created.\n\nPlease Sign in using the link below.");
    }
    else{
        alert("This username has already been made. Please try again.");
    }
    e.preventDefault();
}

function signIn(e) {
    let username = document.getElementById('username').value;
    let formData = JSON.parse(localStorage.getItem('formData')) || [];
    let exist = formData.length &&
    JSON.parse(localStorage.getItem('formData')).some(data => data.username.toLowerCase() == username);
    if(!exist){
        alert("Incorrect login credentials");
    }
    else{
        location.href = "index.html";
    }
    e.preventDefault();
}
