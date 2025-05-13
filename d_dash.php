<?php
session_start();
if (!isset($_SESSION['doctor_name'])) {
  header("Location: d_login.php");
  exit();
}

$doctorName = $_SESSION['doctor_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #fff;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .dashboard {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(12px);
      padding: 40px 50px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
      text-align: center;
      max-width: 500px;
      width: 90%;
    }

    h1 {
      margin-bottom: 30px;
      font-size: 26px;
      font-weight: 600;
      color: #fff;
    }

    .button-container {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .dashboard-button {
      background: #5a85ff;
      color: white;
      padding: 14px;
      border: none;
      border-radius: 30px;
      font-size: 17px;
      cursor: pointer;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .dashboard-button:hover {
      background: #3a5cd0;
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h1>Welcome, Dr. <?php echo htmlspecialchars($doctorName); ?></h1>
    <div class="button-container">
      <a href="appointments.php" class="dashboard-button">Appointments</a>
      <a href="patients.php" class="dashboard-button">Patients</a>
      <a href="work_on.php" class="dashboard-button">Clinic Visits</a>
    </div>
  </div>
</body>
</html>
