<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthsync";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the location and blood group from the form
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$blood_group = isset($_POST['blood_group']) ? trim($_POST['blood_group']) : '';

// Validate inputs
if (empty($location) || empty($blood_group)) {
    die("❌ Invalid input. Please go back and fill out all fields.");
}

// Prepare SQL statement
$stmt = $conn->prepare("SELECT name, age, sex, location, b_group, mobile FROM donors WHERE location = ? AND b_group = ?");
$stmt->bind_param("ss", $location, $blood_group);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Donor Search Results</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #2e3b55;
      color: white;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #a7e245;
    }

    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
      background-color: #ffffff10;
    }

    th, td {
      padding: 14px;
      border-bottom: 1px solid #ffffff30;
      text-align: left;
    }

    th {
      background-color: #a7e245;
      color: #2e3b55;
    }

    tr:hover {
      background-color: #ffffff20;
    }

    .back-link {
      display: block;
      margin-top: 20px;
      text-align: center;
      text-decoration: none;
      color: #a7e245;
      font-weight: bold;
    }

    .no-result {
      text-align: center;
      margin-top: 30px;
      font-size: 18px;
      color: #ff8080;
    }

    .contact-buttons a {
      margin-right: 10px;
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 5px;
      color: white;
      font-weight: bold;
      font-size: 14px;
    }

    .call-btn {
      background-color: #4CAF50;
    }

    .whatsapp-btn {
      background-color: #25D366;
    }
  </style>
</head>
<body>

  <h2>Available Donors in "<?= htmlspecialchars($location) ?>" with Blood Group "<?= htmlspecialchars($blood_group) ?>"</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Sex</th>
        <th>Location</th>
        <th>Blood Group</th>
        <th>Mobile</th>
        <th>Contact</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): 
        $mobile = htmlspecialchars($row['mobile']);
      ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['age']) ?></td>
          <td><?= htmlspecialchars($row['sex']) ?></td>
          <td><?= htmlspecialchars($row['location']) ?></td>
          <td><?= htmlspecialchars($row['b_group']) ?></td>
          <td><?= $mobile ?></td>
          <td class="contact-buttons">
            <a class="call-btn" href="tel:<?= $mobile ?>">Call</a>
            <a class="whatsapp-btn" target="_blank" href="https://wa.me/<?= preg_replace('/\D/', '', $mobile) ?>">WhatsApp</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p class="no-result">❌ No donors found matching your criteria.</p>
  <?php endif; ?>

  <a class="back-link" href="get_a_donor.php">← Back to Search</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
