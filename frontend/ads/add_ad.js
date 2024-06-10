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
const adForm = document.getElementById('add-ad-form');
adForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const inputs = adForm.querySelectorAll('input, textarea');

    const adData = {};
    inputs.forEach(input => {
        adData[input.id] = input.value;
    });
    console.log(adData);
    fetch('../../backend/api/add_ad.php', {
        method: 'POST',
        body: JSON.stringify(adData),
    })
    .then(response=>response.json())
    .then(response=>{
        if(response.success){
            const modal = document.getElementById('popup-container');
            const closeBtn = document.getElementById('popup-close');

            document.getElementById('popup-header').innerText = "Успешно добавена обява :)";
            document.getElementById('popup-text').innerText = adData['name'];
            modal.style.display = 'flex';
            
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
            adForm.reset();
            location.href = 'ads.html';
        }
        else{
            var messageBox = document.getElementById("add-not-success");
            messageBox.style.display = 'block';
            messageBox.innerText = response.message;
        }
    })
});