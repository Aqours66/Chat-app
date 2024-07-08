<?php
// Connect to MySQL
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "chatapp"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle message sending or editing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'send') {
            $sender = $_POST['sender'];
            $message = $_POST['message'];

            $sql = "INSERT INTO messages (sender, message) VALUES ('$sender', '$message')";

            if ($conn->query($sql) === TRUE) {
                echo "Message sent successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } elseif ($action == 'edit') {
            $message_id = $_POST['message_id'];
            $new_message = $_POST['new_message'];

            $sql = "UPDATE messages SET message='$new_message' WHERE id='$message_id'";

            if ($conn->query($sql) === TRUE) {
                echo "Message updated successfully";
            } else {
                echo "Error updating message: " . $conn->error;
            }
        } elseif ($action == 'delete') {
            $message_id = $_POST['message_id'];

            $sql = "DELETE FROM messages WHERE id='$message_id'";

            if ($conn->query($sql) === TRUE) {
                echo "Message deleted successfully";
            } else {
                echo "Error deleting message: " . $conn->error;
            }
        }
    }
}

// Retrieve messages in reverse order (older messages first)
$sql = "SELECT * FROM messages ORDER BY created_at ASC"; // Changed to ASC

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $message_id = $row['id'];
        $sender = $row['sender'];
        $message = $row['message'];
        $timestamp = $row['created_at'];

        echo "<div class='message'>";
        echo "<strong>" . htmlspecialchars($sender) . ":</strong> " . htmlspecialchars($message) . " ";
        echo "<span class='timestamp'>(" . $timestamp . ")</span> ";
        echo "<button class='edit-btn' onclick='editMessage($message_id)'>Edit</button> ";
        echo "<button class='delete-btn' onclick='deleteMessage($message_id)'>Delete</button>";
        echo "</div>";
    }
} else {
    echo "No messages yet";
}

$conn->close();
