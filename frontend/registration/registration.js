// Display additional section
function showAdditionalSection(event, sectionId, userType) {
    const recruiter = document.getElementById('recruiter');
    const graduate = document.getElementById('graduate');
    const additionalSection = document.getElementById(sectionId);
    event.preventDefault();
    var otherSection;
    if(sectionId == 'recruiter-info'){
        state.userType = 'recruiter';
        otherSection = document.getElementById('graduate-info');
    }
    else{
        state.userType = 'graduate';
        otherSection = document.getElementById('recruiter-info');
    }
    recruiter.parentNode.remove();
    graduate.parentNode.remove();
    otherSection.style.display = 'none';
    additionalSection.style.display = 'block';
}

const recruiter = document.getElementById('recruiter');
const graduate = document.getElementById('graduate');

const state = { userType: null };

recruiter.addEventListener('click', (event) => showAdditionalSection(event, 'recruiter-info', state));
graduate.addEventListener('click', (event) => showAdditionalSection(event, 'graduate-info', state));

// Get info from HTML form 
const registrationForm = document.getElementById('register-form');
registrationForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const inputs = registrationForm.querySelectorAll('input, select');

    const userData = {};
    userData['type'] = state['userType'];
    inputs.forEach(input => {
        userData[input.id] = input.value;
    });

    fetch('../../backend/api/registration.php', {
        method: 'POST',
        body: JSON.stringify(userData),
    })
    .then(response=>response.json())
    .then(response=>{
        if(response.success){
            window.location.replace("../login/login.html");
        }
        else{
            var messageBox = document.getElementById("reg-not-success");
            messageBox.style.display = 'block';
            messageBox.innerText = response.message;
        }
    })

});


// CSV case handling

const importForm = document.getElementById('import-form');
importForm.addEventListener('submit', (event) => {
    const file = document.getElementById('import').files[0];
    const formData = new FormData();
    formData.append('csv', file);

    fetch('../backend/api/import_users.php', {
        method: 'POST',
        body: formData 
    });

    event.preventDefault();
});