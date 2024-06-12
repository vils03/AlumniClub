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
};

const joinEvent = (event, id, div) => {
    
    const accept_btn = document.getElementById( id );
    const accepted_msg=document.createElement('h5');
    
    fetch('../../backend/api/add_event_accepted.php', {
        method: 'POST',
        body: JSON.stringify(event),
    })
    .then(response=>response.json())
    .then(response=>{
        if(response.success){
            accept_btn.style.display = 'none';
            accepted_msg.innerText = 'Йей, ще присъствам!';
            div.appendChild(accepted_msg);
        }
    })
}

document.addEventListener('DOMContentLoaded', function(){
    const feedContainer = document.getElementById('accepted-events');
    const eventsContainer = document.getElementById('events-feed');

    const fetchAcceptedData = async() => {
        try {
            const response = await fetch('../../backend/api/get_accepted_events.php');
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

    const fetchEventsData = async() => {
        try {
            const response = await fetch('../../backend/api/get_events.php');
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
        const data = await fetchAcceptedData();
       
        for(var item of data.value){
            const div = document.createElement('div');
            div.classList.add('accepted-event');
            const title = document.createElement('h3');
            title.innerText = item['eventName'];
            div.appendChild(title);

            const date=document.createElement('p');
            date.innerText = item['CreatedEventDateTime'];
            div.appendChild(date);

            feedContainer.appendChild(div);
        }

        const eventData = await fetchEventsData();
        let k = 0;
        eventData.value.forEach(ev => {
            const section = document.createElement('section');
            section.classList.add('event-card');
            /* title, date and description and button -> left side */
            const divInfo = document.createElement('div');
            divInfo.classList.add('event-info');
            section.appendChild(divInfo);

            const title = document.createElement('h2');
            title.innerText = ev['EventName'];
            divInfo.appendChild(title);

            const date=document.createElement('h5');
            console.log(ev);
            date.innerText = ev['CreatedEventDateTime'];
            divInfo.appendChild(date);

            const desc=document.createElement('p');
            desc.innerText = ev['EventDesc'];
            divInfo.appendChild(desc);

            const accept_btn=document.createElement('button');
            accept_btn.innerText = "Ще отида";
            accept_btn.id = 'accept-btn'+(k+1);
            accept_btn.onclick = function() {
                joinEvent(ev, accept_btn.id, divInfo);
                setTimeout(() => {
                    window.location.replace("main.html");
                }, 1000);
            }
            accept_btn.classList.add('accept-event-button');
            divInfo.appendChild(accept_btn); 
            
            /* image -> right side */
            const divImg = document.createElement('div');
            divImg.classList.add('event-img');
            section.appendChild(divImg);

            const image = document.createElement('img');
            console.log(ev['EventImage']);
            image.setAttribute('src', `../../files/uploaded/${ev['EventImage']}`);
            image.setAttribute('alt', 'Event Default');
            divImg.appendChild(image);

            eventsContainer.appendChild(section);
        })
    };

    displayFeed();
});





