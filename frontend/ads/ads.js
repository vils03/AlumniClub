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

document.addEventListener('DOMContentLoaded', function(){
    const adContainer = document.getElementById('rightcolumn');

    const fetchAdInfo = async() => {
        try {
            const response = await fetch('../../backend/api/get_ad.php');
            if(!response.ok){
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            return data;
        }
        catch (error){
            // error
            return [];
        }
    };

    const displayFeed = async () => {
        const data = await fetchAdInfo();
        for(var item of data.value){
            const section = document.createElement('section');
            section.classList.add('job-card');

            const div = document.createElement('div');
            div.classList.add('job-info');
            section.appendChild(div);

            const title = document.createElement('h2');
            title.innerText = item['adName'];
            div.appendChild(title);

            const date=document.createElement('h5');
            date.innerText = item['createdEventDateTime'];
            div.appendChild(date);

            const desc=document.createElement('p');
            desc.innerText = item['adDesc'];
            div.appendChild(desc);

            adContainer.appendChild(section);
        }
    };

    displayFeed();
});


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

