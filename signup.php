<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthsync";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['role'])) {
        $role = $_POST['role'];

        if ($role == "doctor") {
            $name = $_POST['d_name'];
            $mobile = $_POST['d_mobile'];
            $email = $_POST['d_email'];
            $location = $_POST['d_location'];
            $username = $_POST['d_username'];
            $password = $_POST['d_password'];

            // Check if username, email or mobile already exists
            $check_query = $conn->prepare("SELECT * FROM doctors WHERE username = ? OR email = ? OR mobile = ?");
            $check_query->bind_param("sss", $username, $email, $mobile);
            $check_query->execute();
            $check_result = $check_query->get_result();

            if ($check_result->num_rows > 0) {
                $message = "❌ Username or contact/email already exists!";
            } else {
                $stmt = $conn->prepare("INSERT INTO doctors (name, mobile, email, location, username, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $name, $mobile, $email, $location, $username, $password);

                if ($stmt->execute()) {
                    $message = "✅ Signup successful for doctor!";
                } else {
                    $message = "❌ Error: " . $stmt->error;
                }
                $stmt->close();
            }

        } elseif ($role == "patient") {
            $name = $_POST['p_name'];
            $age = $_POST['p_age'];
            $gender = $_POST['p_gender'];
            $email = $_POST['p_email'];
            $username = $_POST['p_username'];
            $password = $_POST['p_password'];

            // Check if username, email already exists
            $check_query = $conn->prepare("SELECT * FROM patients WHERE username = ? OR email = ?");
            $check_query->bind_param("ss", $username, $email);
            $check_query->execute();
            $check_result = $check_query->get_result();

            if ($check_result->num_rows > 0) {
                $message = "❌ Username or contact/email already exists!";
            } else {
                $stmt = $conn->prepare("INSERT INTO patients (name, age, gender, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sissss", $name, $age, $gender, $email, $username, $password);

                if ($stmt->execute()) {
                    $message = "✅ Signup successful for patient!";
                } else {
                    $message = "❌ Error: " . $stmt->error;
                }
                $stmt->close();
            }

        } elseif ($role == "donor") {
            $name = $_POST['donor_name'];
            $contact = $_POST['donor_contact'];
            $blood = $_POST['donor_blood'];
            $email = $_POST['donor_email'];

            $stmt = $conn->prepare("INSERT INTO donors (name, contact, blood, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $contact, $blood, $email);

            if ($stmt->execute()) {
                $message = "✅ Signup successful for donor!";
            } else {
                $message = "❌ Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup Page</title>
  <style>
    body {
      background: #f0f0f0;
      font-family: Arial, sans-serif;
      padding: 20px;
      text-align: center;
    }
    .form-container {
      background: white;
      padding: 20px;
      margin: 20px auto;
      border-radius: 10px;
      width: 400px;
      display: none;
    }
    h2 {
      color: #333;
    }
    label, input, select {
      display: block;
      margin-bottom: 10px;
      width: 100%;
      padding: 8px;
    }
    button {
      background: #4CAF50;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      width: 100%;
    }
    .msg {
      color: green;
      font-weight: bold;
      margin-bottom: 15px;
    }
    .role-buttons {
      margin-bottom: 20px;
    }
    .role-buttons button {
      margin: 5px;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .role-buttons button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

<?php if ($message): ?>
  <div class="msg"><?= $message ?></div>
<?php endif; ?>

<h1>Signup as:</h1>
<div class="role-buttons">
  <button onclick="showForm('doctor')">Doctor</button>
  <button onclick="showForm('patient')">Patient</button>
  <button onclick="showForm('donor')">Donor</button>
</div>

<!-- Doctor Signup Form -->
<div class="form-container" id="doctor-form">
  <h2>Doctor Signup</h2>
  <form method="POST">
    <input type="hidden" name="role" value="doctor">
    <label>Name:</label><input type="text" name="d_name" required>
    <label>Mobile:</label><input type="text" name="d_mobile" required>
    <label>Email:</label><input type="email" name="d_email" required>
    <label>Location:</label><input type="text" name="d_location" required>
    <label>Username:</label><input type="text" name="d_username" required>
    <label>Password:</label><input type="password" name="d_password" required>
    <button type="submit">Sign Up</button>
  </form>
</div>

<!-- Patient Signup Form -->
<div class="form-container" id="patient-form">
  <h2>Patient Signup</h2>
  <form method="POST">
    <input type="hidden" name="role" value="patient">
    <label>Name:</label><input type="text" name="p_name" required>
    <label>Age:</label><input type="number" name="p_age" required>
    <label>Gender:</label>
    <select name="p_gender" required>
      <option value="">--Select--</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
    </select>
    <label>Email:</label><input type="email" name="p_email" required>
    <label>Username:</label><input type="text" name="p_username" required>
    <label>Password:</label><input type="password" name="p_password" required>
    <button type="submit">Sign Up</button>
  </form>
</div>

<!-- Donor Signup Form -->
<div class="form-container" id="donor-form">
  <h2>Donor Signup</h2>
  <form method="POST">
    <input type="hidden" name="role" value="donor">
    <label>Name:</label><input type="text" name="donor_name" required>
    <label>Contact:</label><input type="text" name="donor_contact" required>
    <label>Blood Type:</label><input type="text" name="donor_blood" required>
    <label>Email:</label><input type="email" name="donor_email" required>
    <button type="submit">Sign Up</button>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll(".form-container");
    const buttons = document.querySelectorAll(".role-buttons button");

    window.showForm = function(role) {
        document.getElementById("doctor-form").style.display = "none";
        document.getElementById("patient-form").style.display = "none";
        document.getElementById("donor-form").style.display = "none";

        document.getElementById(role + "-form").style.display = "block";
    };

    forms.forEach(form => {
        form.style.background = "linear-gradient(135deg, #1e3c72, #2a5298)";
        form.style.boxShadow = "0 8px 16px rgba(0, 0, 0, 0.3)";
        form.style.border = "1px solid #ffffff20";
        form.style.transition = "transform 0.3s ease, box-shadow 0.3s ease";
        form.style.color = "#fff";
    });

    forms.forEach(form => {
        form.addEventListener("mouseenter", () => {
            form.style.transform = "translateY(-5px)";
            form.style.boxShadow = "0 12px 24px rgba(0, 0, 0, 0.4)";
        });
        form.addEventListener("mouseleave", () => {
            form.style.transform = "translateY(0)";
            form.style.boxShadow = "0 8px 16px rgba(0, 0, 0, 0.3)";
        });
    });

    buttons.forEach(button => {
        button.style.background = "#2a5298";
        button.style.color = "#fff";
        button.style.border = "1px solid #ffffff20";
        button.style.transition = "background 0.3s ease, transform 0.3s ease";

        button.addEventListener("mouseenter", () => {
            button.style.background = "#3b5998";
            button.style.transform = "scale(1.05)";
        });
        button.addEventListener("mouseleave", () => {
            button.style.background = "#2a5298";
            button.style.transform = "scale(1)";
        });
    });
});
</script>

</body>
</html>
