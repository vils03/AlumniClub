// Display additional section
function showAdditionalSection(event, sectionId) {
    const recruiter = document.getElementById('recruiter');
    const graduate = document.getElementById('graduate');
    const additionalSection = document.getElementById(sectionId);
    event.preventDefault();
    var otherSection;
    if(sectionId == 'recruiter-info'){
        otherSection = document.getElementById('graduate-info');
    }
    else{
        otherSection = document.getElementById('recruiter-info');
    }
    recruiter.parentNode.remove();
    graduate.parentNode.remove();
    otherSection.style.display = 'none';
    additionalSection.style.display = 'block';
    
}

const recruiter = document.getElementById('recruiter');
const graduate = document.getElementById('graduate');

recruiter.addEventListener('click', (event) => showAdditionalSection(event, 'recruiter-info'));
graduate.addEventListener('click', (event) => showAdditionalSection(event, 'graduate-info'));

// Get info from HTML form 
const registrationForm = document.getElementById('register-form');
registrationForm.addEventListener('submit', (event) => {

    const inputs = registrationForm.querySelectorAll('input, select');

    const userData = {};
    inputs.forEach(input => {
        userData[input.id] = input.value;
    });

    console.log(userData);

    event.preventDefault();
});
