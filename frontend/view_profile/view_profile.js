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