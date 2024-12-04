function submitReportForm(event, postId) {
    event.preventDefault();  // Prevent the form from submitting normally
    
    const form = document.getElementById('report-form-' + postId);
    const formData = new FormData(form);
    const modal = document.getElementById('report-modal-' + postId); // Get the modal element
    const statusDiv = document.getElementById('report-status-' + postId); // Div to show success/failure messages

    // Make the AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'report_post.php', true);

    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);

        if (response.success) {
            // Show success message
            statusDiv.innerHTML = `<span style="color: green;">${response.message}</span>`;
            form.reset();  // Clear the form fields
            
            // Close the modal after success
            modal.style.display = 'none';
        } else {
            // Show failure message
            statusDiv.innerHTML = `<span style="color: red;">${response.message}</span>`;
        }
    };

    xhr.onerror = function() {
        statusDiv.innerHTML = '<span style="color: red;">An error occurred. Please try again later.</span>';
    };

    xhr.send(formData);
}
