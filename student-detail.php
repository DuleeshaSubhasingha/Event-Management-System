<?php
session_start(); // Start the session

// Get the event ID from the URL
$eventId = isset($_GET['eventId']) ? $_GET['eventId'] : '';

// Function to get registrations from the JSON file
function loadRegistrations() {
    $file = 'registration.json';
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([])); // Create file if it doesn't exist
    }
    return json_decode(file_get_contents($file), true);
}

// Retrieve registrations from the JSON file
$registrations = loadRegistrations();

// Filter registrations by event ID
$eventRegistrations = array_filter($registrations, function($reg) use ($eventId) {
    return $reg['eventId'] === $eventId;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <style>
         /* General Styles */
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bg3.jpeg'); /* Replace with your image */
            background-size: cover; /* Adjust as necessary */
            background-position: center;
        }

        /* Container Styles */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading Styles */
        h1 {
            text-align: center;
            color: #333;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Button Styles */
        #backButton {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #backButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registered Students for Event ID: <?php echo htmlspecialchars($eventId); ?></h1>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Registration Number</th>
                    <th>Level of Study</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <?php if (!empty($eventRegistrations)): ?>
                    <?php foreach ($eventRegistrations as $reg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reg['firstName']); ?></td>
                            <td><?php echo htmlspecialchars($reg['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($reg['registrationNumber']); ?></td>
                            <td><?php echo htmlspecialchars($reg['levelOfStudy']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No students registered for this event.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <button id="backButton" onclick="goBack()">Back to Events</button>
    </div>
    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page
        }
    </script>
</body>
</html>
