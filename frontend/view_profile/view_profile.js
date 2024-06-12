const imgForm = document.getElementById('change-img');
imgForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const file = document.getElementById('image').files[0];
    const formData = new FormData();
    formData.append('photo', file);

    const inputs = document.querySelectorAll('input');
    const userData = {};
    inputs.forEach(input => {
        userData[input.id] = input.value;
    });

    fetch("../../backend/api/upload_image.php", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) 
    .then(result => {
        console.log('Image uploaded successfully:', result);

        return fetch("../../backend/api/change_profile.php", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            console.log('Profile updated successfully:', response);
        } else {
            console.error('Failed to update profile:', response);
        }
    })
    .catch(error => {
        console.error('Error occurred:', error);
    });
});


// show info for user - API
const partGrad = document.getElementById('additional-grad');
const partRec = document.getElementById('additional-rec');


function changeBtnVisibility (userType) {
    if ( userType === "recruiter") {
        partRec.style.display = 'flex';
        partGrad.style.display = 'none';
    }
    else {
        partRec.style.display = 'none';
        partGrad.style.display = 'flex';
    }
}
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

getUserType();

function changePlaceholders () {
    fetch("../../backend/api/get_user_info.php")
    .then(response => response.json())
    .then(response => {
        if (!response.success) {
            throw new Error('Error get user type.');
        }
        console.log(response.value);
        const userData = response.value;

        const name = document.getElementById('name');
        name.value = userData[0]['FirstName'];
        const lastname = document.getElementById('last-name');
        lastname.value = userData[0]['LastName'];

        const phone = document.getElementById('phone-number');
        phone.value = userData[0]['PhoneNumber'];
        const userType = userData[0]['UserType'];

        console.log(userData);

        if(userType.localeCompare('recruiter') == 0){
            const company = document.getElementById('company');
            company.value = userData[0]['CompanyName'];
        }
        else if(userType.localeCompare('graduate') == 0){
            const fn = document.getElementById('fn');
            fn.value = userData[0]['FN'];
            const major = document.getElementById('major');
            major.value = userData[0]['MajorName'];
            const classuser = document.getElementById('class');
            classuser.value = userData[0]['Class'];
            const loc = document.getElementById('location');
            loc.value = userData[0]['Location'];
            const status = document.getElementById('status');
            const status_bit = userData[0]['Status'];
            if(status_bit) {
                status.value = 'employed';
            }
            else {
                status.value = 'unemployed';
            }
        }
    })
}

changePlaceholders();

const getUser = () => {
    fetch('../../backend/api/get_user_info.php')
    .then(response => response.json())
    .then(response => {
        console.log(response.value);
        return response.value;
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    })
}


const passwordForm = document.getElementById('change-password');

passwordForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const inputs = passwordForm.querySelectorAll('input');

    const passwordData = {};
    inputs.forEach(input => {
        passwordData[input.id] = input.value;
    })

    fetch('../../backend/api/change_password.php', {
        method: 'POST',
        body: JSON.stringify(passwordData)
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            var messageBox = document.getElementById("msg-box");
            messageBox.style.display = 'block';
            messageBox.innerText = 'Успешно променена парола';
        } else {
            var messageBox = document.getElementById("msg-box");
            messageBox.style.display = 'block';
            messageBox.innerText = 'Неуспешно променена парола';
        }
    })
    .catch(error => {
        console.error('Грешка при смяна на парола', error);
        let messageBox = document.getElementById("msg-box");
        messageBox.style.display = 'block';
        messageBox.innerText = 'Грешка при смяна на парола';
    });
});

const UpdateForm = document.getElementById('change-info-form');

UpdateForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const inputs = UpdateForm.querySelectorAll('input, select');

    let userData = {};
    inputs.forEach(input => {
        userData[input.id] = input.value;
    });

    fetch('../../backend/api/get_user_type.php', {
    })
    .then(response=>response.json())
    .then(response=>{
        if (!response.success) {
            throw new Error('Error get user type.');
        }
        else {
            console.log(response.value);
            userData['type'] = response.value;
            fetch('../../backend/api/update_profile.php', {
                method: 'POST',
                body: JSON.stringify(userData),
            })
            .then(response=>response.json())
            .then(response=>{
                let messageBox = document.getElementById("msg");
                if(response.success){
                    messageBox.style.display = 'block';
                    messageBox.innerText = response.message;
                }
                else{
                    messageBox.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Грешка при промяна да данните');
                let messageBox = document.getElementById("msg");
                messageBox.style.display = 'block';
                messageBox.innerText = 'Грешка при промяна да данните';
            });
        }
    })
    .catch(error => {
        console.error('Грешка при извличане тип на потребител');
        let messageBox = document.getElementById("msg");
        messageBox.style.display = 'block';
        messageBox.innerText = 'Грешка при извличане тип на потребител';
    });
    console.log(userData);

});

