function showAdditionalSection(event, sectionId) {
    const additionalSection = document.getElementById(sectionId);
    event.preventDefault();
    var otherSection;
    if(sectionId == 'recruiter-info'){
        otherSection = document.getElementById('graduate-info');
    }
    else{
        otherSection = document.getElementById('recruiter-info');
    }
    otherSection.style.display = 'none';
    additionalSection.style.display = 'block';
    
}

const recruiter = document.getElementById('recruiter');
const graduate = document.getElementById('graduate');

recruiter.addEventListener('click', (event) => showAdditionalSection(event, 'recruiter-info'));
graduate.addEventListener('click', (event) => showAdditionalSection(event, 'graduate-info'));
