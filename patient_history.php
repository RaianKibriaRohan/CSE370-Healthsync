<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_username'])) {
    $_SESSION['selected_patient'] = $_POST['patient_username'];
}

if (!isset($_SESSION['selected_patient'])) {
    die("No patient selected.");
}

$patientUsername = $_SESSION['selected_patient'];

include 'db.php';

// Fetch appointment history
$apptQuery = $conn->prepare("SELECT * FROM appointments WHERE p_username = ?");
$apptQuery->bind_param("s", $patientUsername);
$apptQuery->execute();
$apptResult = $apptQuery->get_result();


// Fetch unique clinic admission history
$admitQuery = $conn->prepare("
    SELECT DISTINCT a.c_name, a.c_location, p.name AS patient_name
    FROM admit a
    JOIN patients p ON a.p_username = p.username
    WHERE a.p_username = ?
");

$admitQuery->bind_param("s", $patientUsername);
$admitQuery->execute();
$admitResult = $admitQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patient History</title>
  <style>
    body {
      background: #2d2f3f;
      color: white;
      font-family: Arial, sans-serif;
    }
    .container {
      max-width: 800px;
      margin: 40px auto;
      background: #444c66;
      padding: 30px;
      border-radius: 10px;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .section-title {
      margin-top: 30px;
      margin-bottom: 10px;
      font-size: 20px;
      border-bottom: 2px solid #aaa;
      padding-bottom: 5px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      color: #000;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background: #f5f5f5;
    }
    .back {
      margin-top: 20px;
      text-align: center;
    }
    .back a {
      color: #ffffff;
      background: #5a85ff;
      padding: 10px 20px;
      border-radius: 20px;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Patient History: <?php echo htmlspecialchars($patientUsername); ?></h2>

    <div class="section-title">Appointment History</div>
    <table>
      <tr><th>Date & Time</th><th>Doctor</th><th>Notes</th></tr>
      <?php while ($row = $apptResult->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['time']); ?></td>
          <td><?php echo htmlspecialchars($row['d_name']); ?></td>
          <td><?php echo htmlspecialchars($row['notes'] ?? 'N/A'); ?></td>
        </tr>
      <?php endwhile; ?>
    </table>

    <div class="section-title">Clinic Admission History</div>
    <table>
      <tr><th>Clinic Name</th><th>Location</th><th>Patient Name</th></tr>
      <?php while ($row = $admitResult->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['c_name']); ?></td>
          <td><?php echo htmlspecialchars($row['c_location']); ?></td>
          <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
        </tr>
      <?php endwhile; ?>
    </table>

    <div class="back">
      <a href="patients.php">← Back to Patient List</a>
    </div>
  </div>
</body>
</html>
