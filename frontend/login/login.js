const loginForm = document.getElementById('login-form');
loginForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const inputs = loginForm.querySelectorAll('input');

    const userData = {};
    inputs.forEach(input => {
        userData[input.id] = input.value;
    });

    fetch('../../backend/api/login.php', {
        method: 'POST',
        body: JSON.stringify(userData),
    })
    .then(response=>response.json())
    .then(response=>{
        if(response.success){
            if(response.email === "admin@admin.com"){
                window.location.replace("../admin_panel/admin.html");
            }
            else{
                window.location.replace("../main_page/main.html");
            }
        }
        else{
            var messageBox = document.getElementById("reg-not-success");
            messageBox.style.display = 'block';
            messageBox.innerText = response.message;
        }
    })
});