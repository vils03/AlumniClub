const buttonReg=document.getElementById("regBtn");
buttonReg.addEventListener('click', (event) => {
    event.preventDefault();
    window.location.replace("../registration/registration.html");
});

const loginReg=document.getElementById("logBtn");
loginReg.addEventListener('click', (event) => {
    event.preventDefault();
    window.location.replace("../login/login.html");
});