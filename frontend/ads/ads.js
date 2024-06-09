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

const addAdBtn = document.getElementById('btn-add-ad');

function getUserType () {
    fetch("../../backend/endpoints/get_user_type.php")
    .then(response => {
      if (!response.ok) {
        throw new Error('Error get user type.');
      }
      return response.json();
    })
    .catch(error => {
      console.error('Грешка при търсенето типа на потребителя.');
    });
}

function changeBtnVisibility () {
    const userType = getUserType();

    if ( userType === "recruiter") {
        addAdBtn.style.display = 'flex';
    }
    else {
        addAdBtn.style.display = 'none';
    }
}

changeBtnVisibility();

addAdBtn.addEventListener('click', () => {
    location.href = 'ad_add.html';
});