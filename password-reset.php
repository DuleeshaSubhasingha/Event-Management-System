<?php
session_start();
$message = "";

// Load existing users from a JSON file
function loadUsers() {
    $file = 'users.json';
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    $json = file_get_contents($file);
    return json_decode($json, true);
}

// Save users to the JSON file
function saveUsers($users) {
    $file = 'users.json';
    file_put_contents($file, json_encode($users));
}

// Password validation
function isValidPassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
}

$username = ""; // Initialize the username variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset'])) {
    $username = $_POST['username']; // Get username from POST data
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Load users
    $users = loadUsers();
    $userFound = false;

    // Check if user exists
    foreach ($users as &$user) {
        if ($user['username'] === $username) {
            $userFound = true;
            // Check if passwords match
            if ($newPassword !== $confirmNewPassword) {
                $message = "Passwords do not match.";
            } elseif (!isValidPassword($newPassword)) {
                $message = "Password must be at least 8 characters long, contain one uppercase letter, one lowercase letter, and one number.";
            } else {
                // Update password
                $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the new password
                saveUsers($users);
                $message = "Password reset successfully! You can now log in with your new password.";
                // Remove header redirect
                break; // Exit the loop
            }
        }
    }

    if (!$userFound) {
        $message = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('bg4.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .reset-container {
            width: 100%;
            max-width: 400px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #007bff;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            display: block;
        }
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        p {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Password Reset</h2>

        <!-- Display message -->
        <?php if (!empty($message)): ?>
            <p style="color: red;"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Password Reset Form -->
        <form method="POST">
            <label for="username">UserName:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>
            <label for="confirmNewPassword">Confirm New Password:</label>
            <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
            <button type="submit" name="reset">Reset Password</button>
        </form>
        <p>Remembered your password? <a href="login-signup.php">Login</a></p>
    </div>
</body>
</html>
