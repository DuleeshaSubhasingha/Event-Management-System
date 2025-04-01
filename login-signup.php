<?php
session_start();

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

// Email validation
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$message = '';
$form = isset($_GET['form']) && $_GET['form'] === 'signup' ? 'signup' : 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST['form'];
    $userType = $_POST['userType'];
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    if ($form === 'login') {
        // Login handling
        $users = loadUsers();
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password']) && $user['type'] === $userType) {
                $_SESSION['currentUser'] = $user;
                header('Location: ' . ($userType === 'student' ? 'student-dashboard.php' : 'organizer-dashboard.php'));
                exit();
            }
        }
        $message = "Invalid username or password.";
    } elseif ($form === 'signup') {
        // Signup handling
        $confirmPassword = $_POST['confirmPassword'];

        if ($password !== $confirmPassword) {
            $message = "Passwords do not match.";
        } elseif (!isValidPassword($password)) {
            $message = "Password must be at least 8 characters long with uppercase, lowercase, and a digit.";
        } else {
            $users = loadUsers();
            foreach ($users as $user) {
                if ($user['username'] === $username) {
                    $message = "Username already exists. Choose another one.";
                    break;
                }
            }
            if (empty($message)) {
                $userData = [
                    'username' => $username,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'type' => $userType
                ];
                
                if ($userType === 'organizer') {
                    if (isset($_POST['organizer_email'])) {
                        $email = htmlspecialchars(trim($_POST['organizer_email']));
                        $committeeName = $_POST['committeeName'];
                        $chairPersonName = htmlspecialchars(trim($_POST['chairPersonName']));
                        if (!isValidEmail($email)) {
                            $message = "Invalid email format.";
                        } else {
                            $userData['organizer_email'] = $email;
                            $userData['committee'] = $committeeName;
                            $userData['chairPerson'] = $chairPersonName;
                        }
                    } else {
                        $message = "Organizer email is required.";
                    }
                } elseif ($userType === 'student') {
                    $firstName = htmlspecialchars(trim($_POST['firstName']));
                    $lastName = htmlspecialchars(trim($_POST['lastName']));
                    $email = htmlspecialchars(trim($_POST['student_email']));
                    $registrationNumber = htmlspecialchars(trim($_POST['registrationNumber']));
                    if (!isValidEmail($email)) {
                        $message = "Invalid email format.";
                    } else {
                        $userData['firstName'] = $firstName;
                        $userData['lastName'] = $lastName;
                        $userData['student_email'] = $email;
                        $userData['registrationNumber'] = $registrationNumber;
                    }
                }

                if (empty($message)) {
                    $users[] = $userData;
                    saveUsers($users);
                    $message = "Signup successful! Please log in.";
                    $form = 'login';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Sign Up</title>
    <style>
        /* General Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Body Styles */
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

        /* Container */
        .auth-container {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Form Titles */
        h2 { font-size: 2em; margin-bottom: 20px; color: #007bff; }

        /* Form Styles */
        form { display: flex; flex-direction: column; align-items: flex-start; }

        label { margin-bottom: 5px; font-weight: bold; color: #555; }

        input[type="text"], input[type="password"], select {
            width: 100%; padding: 10px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 5px; font-size: 1em;
        }

        button[type="submit"] {
            width: 100%; padding: 10px;
            background-color: #007bff; color: #ffffff;
            border: none; border-radius: 5px; cursor: pointer; font-size: 1em;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover { background-color: #0056b3; }

        /* Link Styles */
        p { font-size: 0.9em; color: #666; }
        a { color: #007bff; text-decoration: none; }
        a:hover { color: #0056b3; }
    </style>
    <script>
        function toggleFormFields() {
            var userType = document.getElementById("signupUserType").value;
            var organizerFields = document.getElementById("organizerFields");
            var studentFields = document.getElementById("studentFields");
            organizerFields.style.display = userType === "organizer" ? "block" : "none";
            studentFields.style.display = userType === "student" ? "block" : "none";
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            toggleFormFields();
        });
    </script>
</head>
<body>
    <div class="auth-container">
        <h2><?php echo ucfirst($form); ?></h2>

        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($form === 'signup'): ?>
        <div id="signupFormContainer">
            <form method="POST">
                <input type="hidden" name="form" value="signup">
                <label for="signupUserType">User Type:</label>
                <select id="signupUserType" name="userType" onchange="toggleFormFields()" required>
                    <option value="student">Student</option>
                    <option value="organizer">Organizer</option>
                </select>

                <!-- Organizer Fields -->
                <div id="organizerFields" style="display: none;">
                    <label for="committeeName">Committee Name:</label>
                    <select id="committeeName" name="committeeName">
                        <option value="ComSociety">ComSociety</option>
                        <option value="IEEE-StudentBranch">IEEE-StudentBranch</option>
                        <option value="IEEE-WIE">IEEE-WIE</option>
                    </select>

                    <label for="chairPersonName">Chair Person Name:</label>
                    <input type="text" id="chairPersonName" name="chairPersonName">

                    <label for="organizer_email">E-mail:</label>
                    <input type="text" id="organizer_email" name="organizer_email">
                </div>

                <!-- Student Fields -->
                <div id="studentFields" style="display: block;">
                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" name="firstName">

                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" name="lastName">

                    <label for="student_email">E-mail:</label>
                    <input type="text" id="student_email" name="student_email">

                    <label for="registrationNumber">Registration Number:</label>
                    <input type="text" id="registrationNumber" name="registrationNumber">
                </div>

                <!-- Common Fields -->
                <label for="signupUsername">Username:</label>
                <input type="text" id="signupUsername" name="username" required>
                <label for="signupPassword">Password:</label>
                <input type="password" id="signupPassword" name="password" required>
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="?form=login">Login here</a>.</p>
        </div>

        <?php elseif ($form === 'login'): ?>
        <div id="loginFormContainer">
            <form method="POST">
                <input type="hidden" name="form" value="login">
                <label for="loginUserType">User Type:</label>
                <select id="loginUserType" name="userType" required>
                    <option value="student">Student</option>
                    <option value="organizer">Organizer</option>
                </select>

                <label for="loginUsername">Username:</label>
                <input type="text" id="loginUsername" name="username" required>

                <label for="loginPassword">Password:</label>
                <input type="password" id="loginPassword" name="password" required>

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="?form=signup">Sign up here</a>.</p>
            <p><a href="password-reset.php">Forgot Password?</a></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
