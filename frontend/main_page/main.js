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
        for(var event of eventData.value){
            const section = document.createElement('section');
            section.classList.add('event-card');
            /* title, date and description and button -> left side */
            const divInfo = document.createElement('div');
            divInfo.classList.add('event-info');
            section.appendChild(divInfo);

            const title = document.createElement('h2');
            title.innerText = event['EventName'];
            divInfo.appendChild(title);

            const date=document.createElement('h5');
            date.innerText = event['CreatedEventDateTime'];
            divInfo.appendChild(date);

            const desc=document.createElement('p');
            desc.innerText = event['EventDesc'];
            divInfo.appendChild(desc);

            const accept_btn=document.createElement('button');
            accept_btn.innerText = "Ще отида";
            accept_btn.id = 'accept-btn'+(k+1);
            accept_btn.classList.add('accept-event-button');
            divInfo.appendChild(accept_btn); 
            //event listner for accepting event: 

            console.log(event);

            accept_btn.addEventListener('click', function() {
                accept_btn.style.display = 'none';

                const accepted_msg=document.createElement('h5');
                //accepted_msg.innerText = 'Йей, ще присъствам!';
                divInfo.appendChild(accepted_msg);

                const addEventToUser = async() => {
                    fetch('../../backend/api/add_event_accepted.php', {
                        method: 'POST',
                        body: JSON.stringify(event),
                    })
                    .then(response=>response.json())
                    .then(response=>{
                        if(response.success){
                            accepted_msg.innerText = 'Йей, ще присъствам!';
                        }
                    })
                };

                addEventToUser();
            });

            /* image -> right side */
            const divImg = document.createElement('div');
            divImg.classList.add('event-img');
            section.appendChild(divImg);

            const image = document.createElement('img');
            console.log(event['EventImage']);
            image.setAttribute('src', `../../files/uploaded/${event['EventImage']}`);
            image.setAttribute('alt', 'Event Default');
            divImg.appendChild(image);

            eventsContainer.appendChild(section);
        }
    };

    displayFeed();
});


// const accept_event = document.getElementById('accept-btn');
// accept_event.addEventListener('click', (event) => {
//     event.preventDefault();
//     accept_event.style.display = 'none';

//     const accepted_msg=document.createElement('h5');
//     accepted_msg.innerText = 'Йей, ще присъствам';
//     accept_event.appendChild(accepted_msg);
// });



