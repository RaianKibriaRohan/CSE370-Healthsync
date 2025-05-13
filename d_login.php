<?php
session_start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php'; // your DB connection file

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch doctor using username and password
    $sql = "SELECT name FROM doctors WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $doctor = $result->fetch_assoc();
        $_SESSION['doctor_username'] = $username;
        $_SESSION['doctor_name'] = $doctor['name'];

        echo "<script>alert('Welcome Dr. {$doctor['name']}'); window.location.href='d_dash.php';</script>";
        exit();
    } else {
        $message = "❌ Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Login</title>
  <style>
    body {
      background: #3b4465;
      font-family: Arial, sans-serif;
      color: white;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .form-container {
      background: #2e3550;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
      width: 350px;
      text-align: center;
    }

    h1 {
      margin-bottom: 25px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #5a85ff;
      color: white;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #3a5cd0;
    }

    .error {
      color: #ff6666;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>Doctor Login</h1>
    <?php if (!empty($message)) echo "<p class='error'>$message</p>"; ?>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
