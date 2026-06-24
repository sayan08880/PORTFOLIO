document.getElementById('myForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const number = document.getElementById('number').value;
    const date = document.getElementById('date').value;
    
    if (name === '' || email === '' || number === '' || date === '') {
        showError('All fields are required.');
    } else {
        alert('Form submitted successfully!');
    }
});

document.getElementById('close-alert').addEventListener('click', function() {
    document.getElementById('error-alert').style.display = 'none';
});

function showError(message) {
    document.getElementById('error-message').innerText = message;
    document.getElementById('error-alert').style.display = 'block';
}
