window.onload = function() {
    fetch('../../backend/api/get_user_info.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
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
};