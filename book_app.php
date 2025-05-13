<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['patient_name']) || !isset($_SESSION['patient_username'])) {
    die("Access denied. Please <a href='p_login.php'>log in</a> first.");
}

$patientName = $_SESSION['patient_name'];
$patientUsername = $_SESSION['patient_username'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthsync";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ""; // ✅ Fix: Initialize the variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorUsername = $_POST['doctor_username'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';

    $appointmentDatetime = $date . " " . $time;

    // Get doctor's name
    $doctorQuery = $conn->prepare("SELECT name FROM doctors WHERE username = ?");
    $doctorQuery->bind_param("s", $doctorUsername);
    $doctorQuery->execute();
    $doctorResult = $doctorQuery->get_result();
    $doctorData = $doctorResult->fetch_assoc();
    $doctorName = $doctorData['name'] ?? 'Unknown';
    $doctorQuery->close();

    // Insert appointment
    $stmt = $conn->prepare("INSERT INTO appointments (p_username, p_name, d_username, d_name, time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $patientUsername, $patientName, $doctorUsername, $doctorName, $appointmentDatetime);

    if ($stmt->execute()) {
        $message = "✅ Appointment booked with Dr. <strong>" . htmlspecialchars($doctorName) . "</strong> for patient <strong>" . htmlspecialchars($patientName) . "</strong> on <strong>" . htmlspecialchars($appointmentDatetime) . "</strong>.";
    } else {
        $message = "❌ Failed to book appointment: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch list of doctors
$doctors = [];
$result = $conn->query("SELECT name, username FROM doctors");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #3b4465;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 40px;
    }
    h2 {
      color: #a7e245;
    }
    form {
      background-color: #ffffff10;
      padding: 25px;
      border-radius: 10px;
      width: 350px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    select, input {
      padding: 10px;
      border-radius: 5px;
      border: none;
    }
    button {
      background-color: #a7e245;
      color: #3b4465;
      padding: 10px;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #8dc634;
    }
    .msg {
      margin-top: 20px;
      font-size: 16px;
      color: #a7e245;
      text-align: center;
    }
    option {
      color: black;
    }
  </style>
</head>
<body>
  <h2>Book Appointment</h2>

  <?php if ($message): ?>
    <div class="msg"><?= $message ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="doctor_name">Select Doctor:</label>
    <select name="doctor_username" required>
      <option value="" disabled selected>Select a doctor</option>
      <?php foreach ($doctors as $doc): ?>
        <option value="<?= htmlspecialchars($doc['username']) ?>"><?= htmlspecialchars($doc['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="date">Date:</label>
    <input type="date" name="date" required min="<?= date('Y-m-d') ?>">

    <label for="time">Time:</label>
    <input type="time" name="time" required>

    <button type="submit">Book Appointment</button>
  </form>
</body>
</html>
