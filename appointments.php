<?php
session_start();
if (!isset($_SESSION['doctor_name']) || !isset($_SESSION['doctor_username'])) {
  header("Location: d_login.php");
  exit();
}

include 'db.php';
$doctorName = $_SESSION['doctor_name'];
$doctorUsername = $_SESSION['doctor_username'];

// Handle 'check' submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['check_patient'])) {
  $patientUsernameToCheck = $_POST['patient_username'];
  $patientNameToCheck = $_POST['patient_name'];

  // Insert into checked table
  $checkStmt = $conn->prepare("INSERT INTO checked (d_name, d_username, p_name, p_username) VALUES (?, ?, ?, ?)");
  $checkStmt->bind_param("ssss", $doctorName, $doctorUsername, $patientNameToCheck, $patientUsernameToCheck);
  $checkStmt->execute();
  $checkStmt->close();
}

// Get appointments and join with patient name
$appointmentQuery = $conn->prepare("
  SELECT patients.name AS patient_name, appointments.time, patients.username AS patient_username
  FROM appointments
  JOIN patients ON appointments.p_username = patients.username
  WHERE appointments.d_name = ?
");
$appointmentQuery->bind_param("s", $doctorName);
$appointmentQuery->execute();
$appointmentResult = $appointmentQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      background: #3b4465;
      font-family: Arial, sans-serif;
      color: white;
    }
    .dashboard {
      max-width: 800px;
      margin: 50px auto;
      background: #2e3550;
      padding: 40px;
      border-radius: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      color: #000;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background: #f2f2f2;
    }
    .check-btn {
      background: #4CAF50;
      color: white;
      border: none;
      padding: 6px 12px;
      cursor: pointer;
      border-radius: 4px;
    }
    .nav {
      margin-top: 20px;
      text-align: center;
    }
    .nav a {
      background: #5a85ff;
      color: white;
      padding: 10px 20px;
      border-radius: 20px;
      text-decoration: none;
      margin: 5px;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h1>Appointments for Dr. <?php echo htmlspecialchars($doctorName); ?></h1>

    <table>
      <tr><th>Patient Name</th><th>Time</th><th>Action</th></tr>
      <?php while ($row = $appointmentResult->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
          <td><?php echo htmlspecialchars($row['time']); ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="patient_username" value="<?php echo htmlspecialchars($row['patient_username']); ?>">
              <input type="hidden" name="patient_name" value="<?php echo htmlspecialchars($row['patient_name']); ?>">
              <button type="submit" name="check_patient" class="check-btn">Check</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <div class="nav">
      <a href="patients.php">View Checked Patients</a>
      <a href="d_dash.php">Back to Dashboard</a> <!-- New Button -->
    </div>
  </div>
</body>
</html>
