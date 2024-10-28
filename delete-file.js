function deleteFile(fileName) {
    const formData = new FormData();
    formData.append('fileName', fileName);

    fetch('delete-file.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('File deleted successfully');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}