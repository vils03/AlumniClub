const form = document.getElementById('import-form');

form.addEventListener('submit', (event) => {
    const file = document.getElementById('import').files[0];

    const formData = new FormData();
    formData.append('csv', file);

    fetch('../../backend/api/import_users.php', {
        method: 'POST',
        body: formData 
    })
    .then(response=>response.json())
    .then(response=>{
        if(response.success){
            const modal = document.getElementById('popup-container');
            const closeBtn = document.getElementById('popup-close');

            document.getElementById('popup-header').innerText = "Успешно добавени потребители :)";
            modal.style.display = 'flex';
            
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
        }
    });

    event.preventDefault();
});


const exportForm = document.getElementById('export-section');
exportForm.addEventListener('submit', (event) => {
    fetch('../../backend/api/export_users.php')
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Network response was not ok.');
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'exported_users.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('There was a problem with the fetch operation:', error));

    event.preventDefault();
});