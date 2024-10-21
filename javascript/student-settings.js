function goBack() {
    window.location.href = 'student-page.php';
}

document.getElementById('fileToUpload').addEventListener('change', function(event) {
    const file = event.target.files[0];
        if (file) {
            document.getElementById('saveButton').style.display = 'inline-block';
        }
    reader.readAsDataURL(file);
});

document.getElementById('saveButton').addEventListener('click', function() {
    document.getElementById('saveButton').style.display = 'none';
});

history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
};

function enableEdit() {
    document.getElementById('username').removeAttribute('readonly');
    document.getElementById('name').removeAttribute('readonly');
    document.getElementById('age').removeAttribute('readonly');
    document.getElementById('gr_level').removeAttribute('disabled');
        
    document.getElementById('saveButton').style.display = 'inline-block';
}
    
function saveChanges() {
    const username = document.getElementById('username').value;
    const name = document.getElementById('name').value;
    const age = document.getElementById('age').value;
    const gr_level = document.getElementById('gr_level').value; 
    
    fetch('save-profile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `username=${encodeURIComponent(username)}&name=${encodeURIComponent(name)}&age=${encodeURIComponent(age)}&gr_level=${encodeURIComponent(gr_level)}`
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); 
    })
    .catch(error => {
        console.error('Error:', error);
    });
    
    document.getElementById('username').setAttribute('readonly', true);
    document.getElementById('name').setAttribute('readonly', true);
    document.getElementById('age').setAttribute('readonly', true);
    document.getElementById('gr_level').setAttribute('disabled', true);
        
    document.getElementById('saveButton').style.display = 'none';
}
