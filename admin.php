<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthsync";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$doctors = $conn->query("SELECT * FROM doctors");
$patients = $conn->query("SELECT * FROM patients");
$donors = $conn->query("SELECT * FROM donors");
$clinics = $conn->query("SELECT * FROM clinics");

// Work On: join doctors and clinics
$work_on = $conn->query("
  SELECT 
    d.name AS d_name, 
    d.username AS d_username,
    c.name AS c_name,
    c.location AS c_location,
    w.time
  FROM work_on w
  JOIN doctors d ON w.d_username = d.username
  JOIN clinics c ON w.c_name = c.name
");

// Appointments: show doctor + patient info
$appointments = $conn->query("SELECT * FROM appointments");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #3b4465;
      color: white;
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: #a7e245;
    }
    .tabs {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 20px;
    }
    .tab {
      padding: 10px 20px;
      margin: 5px;
      background: #a7e245;
      color: #3b4465;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }
    .tab:hover {
      background: #8dc634;
    }
    .content {
      display: none;
    }
    .active {
      display: block;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background: #fff;
      color: #000;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background: #f2f2f2;
    }
  </style>
</head>
<body>

<h1>Admin Dashboard</h1>

<div class="tabs">
  <div class="tab" onclick="showTab('doctors')">Doctors</div>
  <div class="tab" onclick="showTab('patients')">Patients</div>
  <div class="tab" onclick="showTab('donors')">Donors</div>
  <div class="tab" onclick="showTab('clinics')">Clinics</div>
  <div class="tab" onclick="showTab('work_on')">Work On</div>
  <div class="tab" onclick="showTab('appointments')">Appointments</div>
</div>

<div id="doctors" class="content active">
  <h2>Doctors</h2>
  <table>
    <tr><th>Name</th><th>Username</th><th>Mobile</th><th>Email</th><th>Location</th></tr>
    <?php while ($row = $doctors->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['mobile']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div id="patients" class="content">
  <h2>Patients</h2>
  <table>
    <tr><th>Name</th><th>Username</th><th>Age</th><th>Gender</th><th>Email</th></tr>
    <?php while ($row = $patients->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['age']) ?></td>
        <td><?= htmlspecialchars($row['gender']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div id="donors" class="content">
  <h2>Donors</h2>
  <table>
    <tr><th>Name</th><th>Age</th><th>Sex</th><th>Location</th><th>Mobile</th><th>Blood Group</th></tr>
    <?php while ($row = $donors->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['age']) ?></td>
        <td><?= htmlspecialchars($row['sex']) ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
        <td><?= htmlspecialchars($row['mobile']) ?></td>
        <td><?= htmlspecialchars($row['b_group']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div id="clinics" class="content">
  <h2>Clinics</h2>
  <table>
    <tr><th>Name</th><th>Beds</th><th>Location</th><th>Contact</th></tr>
    <?php while ($row = $clinics->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['bed']) ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
        <td><?= htmlspecialchars($row['contact']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div id="work_on" class="content">
  <h2>Doctor-Clinic Schedule</h2>
  <table>
    <tr><th>Doctor Name</th><th>Doctor Username</th><th>Clinic Name</th><th>Clinic Location</th><th>Time</th></tr>
    <?php while ($row = $work_on->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['d_name']) ?></td>
        <td><?= htmlspecialchars($row['d_username']) ?></td>
        <td><?= htmlspecialchars($row['c_name']) ?></td>
        <td><?= htmlspecialchars($row['c_location']) ?></td>
        <td><?= htmlspecialchars($row['time']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<div id="appointments" class="content">
  <h2>Appointments</h2>
  <table>
    <tr>
      <th>Doctor Name</th>
      <th>Doctor Username</th>
      <th>Patient Name</th>
      <th>Patient Username</th>
      <th>Time</th>
    </tr>
    <?php while ($row = $appointments->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['d_name']) ?></td>
        <td><?= htmlspecialchars($row['d_username']) ?></td>
        <td><?= htmlspecialchars($row['p_name']) ?></td>
        <td><?= htmlspecialchars($row['p_username']) ?></td>
        <td><?= htmlspecialchars($row['time']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<script>
  function showTab(id) {
    document.querySelectorAll('.content').forEach(c => c.classList.remove('active'));
    document.getElementById(id).classList.add('active');
  }
</script>

</body>
</html>
