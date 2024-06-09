window.onload = function() {
    fetch('../../backend/api/get_user_info.php')
        .then(response => response.json())
        .then(data => {
            const nameElement = document.getElementById('name');
            nameElement.innerText = data.value[0]['FirstName'];

            const userInfoElement = document.getElementById('user-info');
            const userType = data.value[0]['UserType'];
            if(userType.localeCompare('recruiter') == 0){
                userInfoElement.innerText = data.value[0]['CompanyName'];
            }
            else if(userType.localeCompare('graduate') == 0){
                userInfoElement.innerText = data.value[0]['MajorName'] + ', ' + data.value[0]['Class'];
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}


const eventForm = document.getElementById('add-event-form');
eventForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const inputs = eventForm.querySelectorAll('input, textarea');

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
            const modal = document.getElementById('popup-container');
            const closeBtn = document.getElementById('popup-close');

            document.getElementById('popup-header').innerText = "Успешно добавено събитие :)";
            document.getElementById('popup-text').innerText = eventData['name'];
            modal.style.display = 'flex';
            
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
            eventForm.reset();
        }
        else{
            var messageBox = document.getElementById("reg-not-success");
            messageBox.style.display = 'block';
            messageBox.innerText = response.message;
        }
    })
});