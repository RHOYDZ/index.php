<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "your_database";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages
$sql = "SELECT id, name, email, submitted_at, message_status, message FROM contact_messages ORDER BY submitted_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="mesages.css">

</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="../pic/logo2.png" alt="Logo" class="sidebar-logo">
        <ul class="sidebar-menu">
            <li><a href="mesages.php" class="sidebar-link">Messages</a></li>
            <li><a href="index.php" class="sidebar-link">Dashboard</a></li>
            <li><a href="users.php" class="sidebar-link">Users</a></li>
        </ul>
        <div class="logout">
            <a href="../frontend/login.php" class="logout-link">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">Messages</h1>
        <table class="message-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date Sent</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr data-message-id="<?php echo $row['id']; ?>" data-message="<?php echo htmlspecialchars($row['message']); ?>" class="message-row">
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo date("F j, Y, g:i a", strtotime($row['submitted_at'])); ?></td>
                            <td class="status">
                                <?php if ($row['message_status'] == 0): ?>
                                    <span class="unread">Unread</span>
                                <?php else: ?>
                                    <span class="read">Read</span>
                                    <button class="delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-messages">No messages found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for message content -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <div class="modal-header">Message Content</div>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        // Modal handling
        const modal = document.getElementById('messageModal');
        const modalMessage = document.getElementById('modalMessage');
        const modalClose = document.querySelector('.modal-close');

        // Open modal on row click
        document.querySelectorAll('.message-row').forEach(row => {
            row.addEventListener('click', function() {
                const message = row.getAttribute('data-message');
                const messageId = row.getAttribute('data-message-id');

                // Show the message in the modal
                modalMessage.textContent = message;
                modal.style.display = 'block';

                // If the message is unread, update it to "read"
                if (row.querySelector('.unread')) {
                    fetch('update_message_status.php', {
                            method: 'POST',
                            body: JSON.stringify({ id: messageId }),
                            headers: { 'Content-Type': 'application/json' }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                row.querySelector('.unread').textContent = 'Read';
                                row.querySelector('.unread').classList.remove('unread');
                                row.querySelector('.unread').classList.add('read');
                            } else {
                                alert('Failed to mark the message as read.');
                            }
                        })
                        .catch(error => console.error('Error updating message status:', error));
                }
            });
        });

        // Close modal
        modalClose.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });

        // Handle delete button click
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent row click event from triggering

                const messageId = this.getAttribute('data-id');

                fetch('delete_message.php', {
                        method: 'POST',
                        body: JSON.stringify({ id: messageId }),
                        headers: { 'Content-Type': 'application/json' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const row = this.closest('tr');
                            row.remove();
                        } else {
                            alert('Failed to delete the message.');
                        }
                    })
                    .catch(error => console.error('Error deleting message:', error));
            });
        });
    </script>

</body>


<?php
$conn->close();
?>