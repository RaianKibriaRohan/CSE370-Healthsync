<?php
session_start();

// Check if patient is logged in using the username and actual name
if (!isset($_SESSION['patient_username']) || !isset($_SESSION['patient_name'])) {
    die("Access denied. Please <a href='p_login.php'>log in</a> first.");
}

// Use the specific session variables as set by p_login.php
$patientUsername = $_SESSION['patient_username']; // The login username
$patientDisplayName = $_SESSION['patient_name'];   // The patient's actual name for display

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "healthsync";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle Admit action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admit']) && isset($_POST['clinic_name']) && isset($_POST['clinic_location']) && isset($_POST['disease'])) {
    $clinicName = $_POST['clinic_name'];
    $clinicLocation = $_POST['clinic_location'];
    $disease = $_POST['disease'];

    if (empty(trim($disease))) {
        $message = "❌ Disease information is required.";
    } else {
        // Insert into 'admit' table with patient's username and full name
        $stmt = $conn->prepare("INSERT INTO admit (p_username, p_name, c_name, c_location, disease) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $patientUsername, $patientDisplayName, $clinicName, $clinicLocation, $disease);

        if ($stmt->execute()) {
            $message = "✅ You are admitted to <strong>" . htmlspecialchars($clinicName) . "</strong> clinic for treatment of <strong>" . htmlspecialchars($disease) . "</strong>.";

            // Insert into history table
            $now = date('Y-m-d H:i:s');
            $historyStmt = $conn->prepare("INSERT INTO history (p_name, c_name, admit_time) VALUES (?, ?, ?)");
            $historyStmt->bind_param("sss", $patientDisplayName, $clinicName, $now);
            $historyStmt->execute();
            $historyStmt->close();
        } else {
            $message = "❌ Admission failed: " . $stmt->error;
        }
        $stmt->close();
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admit'])) {
    if (!isset($_POST['disease']) || empty(trim($_POST['disease']))) {
        $message = "❌ Please enter the disease information.";
    } else if (!isset($_POST['clinic_location'])) {
         $message = "❌ Clinic location information is missing.";
    } else {
        $message = "❌ Required information missing for admission.";
    }
}

// Fetch clinics
$sql = "SELECT name, bed, location, contact FROM clinics";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admit to Clinic</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #3b4465;
      color: white;
      padding: 20px;
    }
    h2 {
      text-align: center;
      color: #a7e245;
    }
    table {
      width: 100%;
      margin-top: 20px;
      border-collapse: collapse;
      background-color: #ffffff10;
    }
    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ffffff30;
    }
    th {
      background-color: #a7e245;
      color: #3b4465;
    }
    tr:hover {
      background-color: #ffffff20;
    }
    .msg {
      text-align: center;
      font-size: 18px;
      margin-top: 15px;
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 5px;
    }
    .msg.success {
        color: #a7e245;
        background-color: rgba(167, 226, 69, 0.1);
        border: 1px solid #a7e245;
    }
    .msg.error {
        color: #ff6b6b;
        background-color: rgba(255, 107, 107, 0.1);
        border: 1px solid #ff6b6b;
    }
    form .disease-input {
        padding: 8px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
        width: calc(100% - 18px);
        box-sizing: border-box;
        background-color: #ffffff;
        color: #333;
    }
    form button {
      padding: 8px 12px;
      font-weight: bold;
      background-color: #a7e245;
      color: #3b4465;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      box-sizing: border-box;
    }
    form button:hover {
      background-color: #8dc634;
    }
  </style>
</head>
<body>
  <h2>Welcome, <?= htmlspecialchars($patientDisplayName) ?> 👋 — Choose a Clinic to Admit</h2>

  <?php if (!empty($message)): ?>
    <div class="msg <?= strpos($message, '✅') !== false ? 'success' : (strpos($message, '❌') !== false ? 'error' : '') ?>">
        <?= $message ?>
    </div>
  <?php endif; ?>

  <table>
    <tr>
      <th>Clinic Name</th>
      <th>Beds Available</th>
      <th>Location</th>
      <th>Contact</th>
      <th>Disease & Action</th>
    </tr>

    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["bed"]) . "</td>
                    <td>" . htmlspecialchars($row["location"]) . "</td>
                    <td>" . htmlspecialchars($row["contact"]) . "</td>
                    <td>
                      <form method='POST' action=''>
                        <input type='hidden' name='clinic_name' value='" . htmlspecialchars($row["name"]) . "' />
                        <input type='hidden' name='clinic_location' value='" . htmlspecialchars($row["location"]) . "' />
                        <input type='text' name='disease' class='disease-input' placeholder='Enter disease' required />
                        <button type='submit' name='admit'>Admit</button>
                      </form>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No clinics available.</td></tr>";
    }
    $conn->close();
    ?>
  </table>
</body>
</html>
