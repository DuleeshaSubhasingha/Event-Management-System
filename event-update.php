<?php
session_start();

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['currentUser']) || $_SESSION['currentUser']['type'] !== 'organizer') {
    header('Location: login-signup.php'); // Redirect to login if not logged in
    exit();
}

// Function to get event data from the JSON file by ID
function getEventById($id) {
    $file = 'events.json';
    if (file_exists($file)) {
        $events = json_decode(file_get_contents($file), true);
        foreach ($events as $event) {
            if ($event['id'] === $id) {
                return $event; // Return the matched event
            }
        }
    }
    return [];
}

$message = '';
$eventData = [];

// Load event data if an ID is provided in the URL
if (isset($_GET['id'])) {
    $eventData = getEventById($_GET['id']);
    if (!$eventData) {
        $message = "Event not found.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather form data
    $eventId = $_POST['eventId'];
    $eventName = $_POST['eventName'];
    $organizingCommittee = $_POST['organizingCommittee'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $venue = $_POST['venue'];
    $speakerName = $_POST['speakerName'];

    // Create updated event object
    $updatedEvent = [
        'id' => $eventId,
        'name' => $eventName,
        'committee' => $organizingCommittee,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'startTime' => $startTime,
        'endTime' => $endTime,
        'venue' => $venue,
        'speaker' => $speakerName,
    ];

    // Update the events in the JSON file
    $file = 'events.json';
    $events = json_decode(file_get_contents($file), true);
    
    // Find and update the specific event
    foreach ($events as &$event) {
        if ($event['id'] === $eventId) {
            $event = $updatedEvent; // Update the event details
            break;
        }
    }
    file_put_contents($file, json_encode($events, JSON_PRETTY_PRINT));

    // Redirect to the event dashboard after updating
    header('Location: organizer-dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General page styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('bg3.jpeg'); /* Replace with your image */
            background-size: cover; /* Adjust as necessary */
            background-repeat: no-repeat;
            background-position: center;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container for the form */
        .event-container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        h2 {
            color: #007bff;
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
            background-color: #fdfdfd;
            transition: all 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.25);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: #d9534f;
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .back-button {
            margin-top: 10px;
            text-align: center;
        }

        .back-button button {
            width: auto;
            padding: 8px 20px;
            background-color: #6c757d;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="event-container">
        <h2>Update Event</h2>

        <!-- Display message if any -->
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Event update form -->
        <form method="POST">
            <input type="hidden" id="eventId" name="eventId" value="<?php echo htmlspecialchars($eventData['id'] ?? ''); ?>">

            <label for="eventName">Event Name:</label>
            <input type="text" id="eventName" name="eventName" value="<?php echo htmlspecialchars($eventData['name'] ?? ''); ?>" required>
            
            <label for="organizingCommittee">Organizing Committee:</label>
            <input type="text" id="organizingCommittee" name="organizingCommittee" value="<?php echo htmlspecialchars($eventData['committee'] ?? ''); ?>" required>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($eventData['startDate'] ?? ''); ?>" required>

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($eventData['endDate'] ?? ''); ?>" required>

            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime" value="<?php echo htmlspecialchars($eventData['startTime'] ?? ''); ?>" required>

            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime" value="<?php echo htmlspecialchars($eventData['endTime'] ?? ''); ?>" required>

            <label for="venue">Venue:</label>
            <select id="venue" name="venue" required>
                <option value="">Select Venue</option>
                <option value="DCS Auditorium" <?php echo (isset($eventData['venue']) && $eventData['venue'] === 'DCS Auditorium') ? 'selected' : ''; ?>>DCS Auditorium</option>
                <option value="CSL 1 & 2" <?php echo (isset($eventData['venue']) && $eventData['venue'] === 'CSL 1 & 2') ? 'selected' : ''; ?>>CSL 1 & 2</option>
                <option value="CSL 3 & 4" <?php echo (isset($eventData['venue']) && $eventData['venue'] === 'CSL 3 & 4') ? 'selected' : ''; ?>>CSL 3 & 4</option>
            </select>

            <label for="speakerName">Speaker Name:</label>
            <input type="text" id="speakerName" name="speakerName" value="<?php echo htmlspecialchars($eventData['speaker'] ?? ''); ?>" required>

            <button type="submit">Update Event</button>
        </form>

        <!-- Back button to return to the event list -->
        <div class="back-button">
            <button onclick="goBack()">Back to Events</button>
        </div>
    </div>

    <!-- Back button functionality -->
    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page
        }
    </script>
</body>
</html>
