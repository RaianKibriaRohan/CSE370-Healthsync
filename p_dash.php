<?php
session_start();

// Ensure patient is logged in
if (!isset($_SESSION['patient_name']) || !isset($_SESSION['patient_username'])) {
  header("Location: p_login.php");
  exit();
}

// Fetch patient details from session
$patientName = $_SESSION['patient_name'];
$patientUsername = $_SESSION['patient_username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .dashboard-container {
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      text-align: center;
      width: 300px;
    }

    h2 {
      color: #3b4465;
      margin-bottom: 30px;
    }

    .dashboard-button {
      display: block;
      width: 100%;
      padding: 15px;
      margin: 10px 0;
      background-color: #a7e245;
      color: #3b4465;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .dashboard-button:hover {
      background-color: #8dc634;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($patientName); ?> 👋</h2>

    <!-- Add patient username dynamically to the links -->
    <a href="p_c.php?username=<?php echo urlencode($patientUsername); ?>" class="dashboard-button">Admit to Clinic</a>
    <a href="book_app.php?username=<?php echo urlencode($patientUsername); ?>" class="dashboard-button">Book Appointment with Doctor</a>
    <a href="get_a_donor.php?username=<?php echo urlencode($patientUsername); ?>" class="dashboard-button">Get a Donor</a>
  </div>
</body>
</html>
