const loginForm = document.getElementById('add-event-form');
loginForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const inputs = loginForm.querySelectorAll('input');

    const eventData = {};
    inputs.forEach(input => {
        eventData[input.id] = input.value;
    });

    fetch('../../backend/api/add_event.php', {
        method: 'POST',
        body: JSON.stringify(eventData),
    })
    .then(response=>response.json())
    .then(response=>{
        if(response.success){
            let messageBox = document.getElementById("reg-success");
            messageBox.style.display = 'block';
            messageBox.innerText = "bravo";
        }
        else{
            let messageBox = document.getElementById("reg-not-success");
            messageBox.style.display = 'block';
            messageBox.innerText = response.message;
        }
    })
});