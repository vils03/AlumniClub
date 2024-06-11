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
        partRec.style.display = 'block';
        partGrad.style.display = 'none';
    }
    else {
        partRec.style.display = 'none';
        partGrad.style.display = 'block';
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
        const userData = response.value;

        const name = document.getElementById('name');
        name.value = userData[0]['FirstName'];
        const lastname = document.getElementById('last-name');
        lastname.value = userData[0]['LastName'];

        const phone = document.getElementById('phone-number');
        phone.value = userData[0]['PhoneNumber'];
        const userType = userData[0]['UserType'];

        if(userType.localeCompare('recruiter') == 0){
            const company = document.getElementById('company');
            company.value = userData[0]['CompanyName'];
        }
        else if(userType.localeCompare('graduate') == 0){
            const fn = document.getElementById('fn');
            fn.value = userData[0]['FN'];
            const major = document.getElementById('major');
            major.value = userData[0]['Major'];
            const classuser = document.getElementById('class');
            classuser.value = userData[0]['Class'];
            const loc = document.getElementById('location');
            loc.value = userData[0]['Location'];
            const status = document.getElementById('status');
            status.value = userData[0]['Status'];
        }
    })
}

changePlaceholders();