<?php
session_start();
if (!isset($_SESSION['doctor_name'])) {
  header("Location: d_login.php");
  exit();
}

include 'db.php';
$doctorName = $_SESSION['doctor_name'];

// Fetch checked patients with their username
$query = $conn->prepare("
  SELECT p.name, p.username, p.gender, p.age, p.email
  FROM checked c
  JOIN patients p ON c.p_username = p.username
  WHERE c.d_name = ?
");
$query->bind_param("s", $doctorName);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checked Patients</title>
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
    }
    th {
      background: #f2f2f2;
    }
    .btn-history {
      background-color: #4CAF50;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
      cursor: pointer;
    }
    .btn-history:hover {
      background-color: #45a049;
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
    <h1>Checked Patients by Dr. <?php echo htmlspecialchars($doctorName); ?></h1>
    <table>
      <tr><th>Name</th><th>Gender</th><th>Age</th><th>Email</th><th>Action</th></tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['gender']); ?></td>
          <td><?php echo htmlspecialchars($row['age']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td>
            <form method="POST" action="patient_history.php" style="display:inline;">
              <input type="hidden" name="patient_username" value="<?php echo htmlspecialchars($row['username']); ?>">
              <button type="submit" class="btn-history">View History</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <div class="nav">
      <a href="d_dash.php">Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
