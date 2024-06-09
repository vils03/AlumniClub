window.onload = function() {
    fetch('../../backend/api/get_user_info.php')
        .then(response => response.json())
        .then(data => {
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

        // fetch('../../backend/api/get_events.php')
        // .then(response => response.json())
        // .then(data => {
        //     const eventNameElement = document.getElementById('event-name');
        //     eventNameElement.innerText = data.value[0]['EventName'];

        //     const eventDateElement = document.getElementById('event-date');
        //     eventDateElement.innerText = data.value[0]['CreatedEventDateTime'];

        //     const eventDescElement = document.getElementById('event-desc');
        //     eventDescElement.innerText = data.value[0]['EventDesc'];
        // })
        // .catch(error => {
        //     console.error('Error fetching events data:', error);
        // });

        // fetch('../../backend/api/get_accepted_events.php')
        // .then(response => response.json())
        // .then(data => {
        //     const eventNameElement = document.getElementById('accepted-name');
        //     eventNameElement.innerText = data.value[0]['eventName'];

        //     const eventDateElement = document.getElementById('accepted-date');
        //     eventDateElement.innerText = data.value[0]['CreatedEventDateTime'];
        // })
        // .catch(error => {
        //     console.error('Error fetching events data:', error);
        // });

        
        
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
        for(var event of eventData.value){
            const section = document.createElement('section');
            section.classList.add('event-card');
            /* title, date and description -> left side */
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
