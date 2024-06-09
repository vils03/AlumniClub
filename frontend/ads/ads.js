const addAdBtn = document.getElementById('btn-add-ad');

function getUserType () {
    fetch('../../backend/api/get_user_info.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData)
    })
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

addAdBtn.addEventListener('click', () => {
    location.href = 'ad_add.html';
});