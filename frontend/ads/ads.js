window.onload = function() {
    fetch('../../backend/api/get_user_info.php')
        .then(response => response.json())
        .then(data => {
            const nameElement = document.getElementById('name');
            nameElement.innerText = data.value[0]['FirstName'];
            const imgElem = document.getElementById('profile-pic');
            imgElem.setAttribute('src', `../../files/uploaded/${data.value[0]['UserImage']}`);
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
    fetch("../../backend/api/get_user_type.php")
    .then(response => response.json())
    .then(response => {
      if (!response.success) {
        throw new Error('Error get user type.');
      }
      changeBtnVisibility(response.value);
    })
}

function changeBtnVisibility (userType) {
    if ( userType === "recruiter") {
        addAdBtn.style.display = 'block';
        addAdBtn.addEventListener('click', () => {
            location.href = 'add_ad.html';
        });
    }
    else {
        addAdBtn.style.display = 'none';
    }
}

getUserType();

