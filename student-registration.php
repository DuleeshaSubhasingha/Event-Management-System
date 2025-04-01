<?php
session_start(); // Start the session for user management
$eventId = isset($_POST['eventId']) ? $_POST['eventId'] : '';

// Initialize variables for error and success messages
$error = '';
$success = '';

// Function to save registrations to JSON file
function saveRegistrations($registrations) {
    $file = 'registration.json';
    file_put_contents($file, json_encode($registrations, JSON_PRETTY_PRINT));
}

// Function to get registrations from JSON file
function getRegistrations() {
    $file = 'registration.json';
    if (!file_exists($file)) {
        return []; // Return an empty array if the file doesn't exist
    }
    return json_decode(file_get_contents($file), true);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather form data
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $registrationNumber = trim($_POST['registrationNumber']);
    $levelOfStudy = $_POST['levelOfStudy'];
    $eventId = isset($_POST['eventId']) ? $_POST['eventId'] : '';

    // Validate form data
    if (empty($firstName) || empty($lastName) || empty($registrationNumber) || empty($levelOfStudy)) {
        $error = "Please fill in all fields.";
    } else {
        // Fetch existing registrations from the JSON file
        $registrations = getRegistrations();

        // Check for duplicate registration number within the same event
        foreach ($registrations as $reg) {
            if ($reg['eventId'] === $eventId && $reg['registrationNumber'] === $registrationNumber) {
                $error = "Registration number already exists for this event. Please use a different one.";
                break;
            }
        }

        // If no errors, add new registration data
        if (empty($error)) {
            $registrationData = [
                'eventId' => $eventId,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'registrationNumber' => $registrationNumber,
                'levelOfStudy' => $levelOfStudy,
                'type' => 'student'
            ];
            $registrations[] = $registrationData; // Add new registration

            // Save registrations to JSON file
            saveRegistrations($registrations);

            $success = "Registration successful!";
            // Optionally, redirect to the dashboard
            // header("Location: student-dashboard.php");
            // exit();
        }
    }
}

// Get the event ID from the URL

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            background-image: url('bg2.jpeg'); /* Replace with your image */
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input[type="text"], select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        #backButton {
            background-color: #007bff;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }
        #backButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Event Registration</h2>

        <!-- Display error or success message -->
        <div class="message">
            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif ($success): ?>
                <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
        </div>

        <!-- Registration Form -->
        <form id="studentRegistrationForm" method="POST" action="">
            <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($eventId); ?>">

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="registrationNumber">Registration Number:</label>
            <input type="text" id="registrationNumber" name="registrationNumber" required>

            <label for="levelOfStudy">Level of Study:</label>
            <select name="levelOfStudy" id="levelOfStudy" required>
                <option value="">Select here</option>
                <option value="Level 1">Level 1</option>
                <option value="Level 2">Level 2</option>
                <option value="Level 3">Level 3</option>
                <option value="Level 4">Level 4</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <button id="backButton" onclick="goBack()">Back to Events</button>
    </div>
    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page
        }
    </script>
</body>
</html>
