const form = document.getElementById('import-form');

form.addEventListener('submit', (event) => {
    const file = document.getElementById('import').files[0];

    const formData = new FormData();
    formData.append('csv', file);

    fetch('../../backend/api/import_users.php', {
        method: 'POST',
        body: formData 
    });

    event.preventDefault();
});