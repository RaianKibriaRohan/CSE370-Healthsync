<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    // Get username and password from POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // SQL query to check if the patient exists with this username and password
    $sql = "SELECT * FROM patients WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);  // 'ss' means two strings (username, password)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If patient is found, store their name in session and redirect to dashboard
        $patient = $result->fetch_assoc();
        $_SESSION['patient_name'] = $patient['name'];  // Store the patient's name in session
        $_SESSION['patient_username'] = $username;  // Store the patient's username in session
        header("Location: p_dash.php");  // Redirect to the patient dashboard
        exit();
    } else {
        $message = "Invalid credentials. Please try again.";  // Display error message if invalid credentials
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="form-container">
    <h1>Patient Login</h1>

    <?php if (!empty($message)) echo "<p style='color:red;'>$message</p>"; ?>

    <form class="themed-form" method="POST" action="p_login.php">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.querySelector('.themed-form');
      const inputs = form.querySelectorAll('input');
      const message = document.createElement('p');
      message.style.color = 'red';
      form.insertBefore(message, form.firstChild);

      inputs.forEach(input => {
        input.addEventListener('input', function() {
          if (input.validity.valid) {
            input.style.borderColor = 'green';
            message.textContent = '';
          } else {
            input.style.borderColor = 'red';
            message.textContent = 'Please fill out all fields correctly.';
          }
        });
      });

      form.addEventListener('submit', function(event) {
        let allValid = true;
        inputs.forEach(input => {
          if (!input.validity.valid) {
            allValid = false;
            input.style.borderColor = 'red';
          }
        });

        if (!allValid) {
          event.preventDefault();
          message.textContent = 'Please correct the errors before submitting.';
        } else {
          message.textContent = 'Logging in...';
        }
      });
    });
  </script>
</body>
</html>
