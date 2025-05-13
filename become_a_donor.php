<?php
$success = null;
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name       = trim($_POST['name']);
    $age        = trim($_POST['age']);
    $sex        = trim($_POST['sex']);
    $mobile     = trim($_POST['mobile']);
    $location   = trim($_POST['location']);
    $b_group    = trim($_POST['blood_group']);

    if ($name && $age && $sex && $mobile && $location && $b_group) {
        $conn = new mysqli("localhost", "root", "", "healthsync");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if mobile number already exists
        $check = $conn->prepare("SELECT * FROM donors WHERE mobile = ?");
        $check->bind_param("s", $mobile);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $success = false;
            $error_message = "Mobile number already registered.";
        } else {
            // Insert new donor
            $stmt = $conn->prepare("INSERT INTO donors (name, age, sex, mobile, location, b_group) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissss", $name, $age, $sex, $mobile, $location, $b_group);

            if ($stmt->execute()) {
                $success = true;
            } else {
                $success = false;
                $error_message = "Database error. Try again.";
            }
            $stmt->close();
        }

        $check->close();
        $conn->close();
    } else {
        $success = false;
        $error_message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Become a Donor</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(45deg, #ff6b6b, #ffcc5c, #6bff6b, #4c6bff);
      background-size: 400% 400%;
      animation: gradient 10s ease infinite;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
      flex-direction: column;
    }

    @keyframes gradient {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    h1 {
      font-size: 40px;
      color: white;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .form-container {
      background-color: white;
      color: #333;
      padding: 30px;
      border-radius: 15px;
      width: 400px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .form-container input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .form-container button {
      width: 100%;
      padding: 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 18px;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: #45a049;
    }

    .form-container label {
      text-align: left;
      font-size: 16px;
      display: block;
    }

    .message {
      margin-top: 20px;
      padding: 12px;
      border-radius: 8px;
      color: white;
      font-size: 18px;
      font-weight: bold;
    }

    .success {
      background-color: #28a745;
    }

    .error {
      background-color: #dc3545;
    }
  </style>
</head>
<body>

  <h1>Become a Donor</h1>

  <div class="form-container">
    <form method="POST" action="">
      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" required placeholder="Enter your full name">

      <label for="age">Age:</label>
      <input type="text" id="age" name="age" required placeholder="Enter your age">

      <label for="sex">Sex:</label>
      <input type="text" id="sex" name="sex" required placeholder="Enter your gender">

      <label for="mobile">Phone Number:</label>
      <input type="text" id="mobile" name="mobile" required placeholder="Enter your phone number">

      <label for="location">Location:</label>
      <input type="text" id="location" name="location" required placeholder="Enter your location">

      <label for="blood_group">Blood Group:</label>
      <input type="text" id="blood_group" name="blood_group" required placeholder="Enter your blood group">

      <button type="submit">Submit</button>
    </form>

    <?php if ($success !== null): ?>
      <div class="message <?= $success ? 'success' : 'error' ?>">
        <?= $success ? '✅ Donor registered successfully!' : '❌ ' . $error_message ?>
      </div>
    <?php endif; ?>
  </div>

</body>
</html>
