document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();

    var sender = document.getElementById('sender').value;
    var message = document.getElementById('message').value;

    fetch('chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'sender=' + sender + '&message=' + message,
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Print server response
            document.getElementById('message').value = ''; // Clear message input
            fetchMessages(); // Refresh messages
        });
});

function fetchMessages() {
    fetch('chat.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('message-container').innerHTML = data;
        });
}

// Fetch messages initially
fetchMessages();

// Poll every 5 seconds for new messages
setInterval(fetchMessages, 5000);

// Function to edit a message
function editMessage(messageId) {
    var newMessage = prompt("Enter the new message:");
    if (newMessage !== null && newMessage !== '') {
        fetch('chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=edit&message_id=' + messageId + '&new_message=' + encodeURIComponent(newMessage),
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Print server response
                fetchMessages(); // Refresh messages
            });
    }
}

// Function to delete a message
function deleteMessage(messageId) {
    if (confirm("Are you sure you want to delete this message?")) {
        fetch('chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=delete&message_id=' + messageId,
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Print server response
                fetchMessages(); // Refresh messages
            });
    }
}