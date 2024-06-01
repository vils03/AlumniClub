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

    const inputs = registrationForm.querySelectorAll('input, select');

    const userData = {};
    userData['type'] = state['userType'];
    inputs.forEach(input => {
        userData[input.id] = input.value;
    });

    console.log(userData);

    event.preventDefault();
});
