<?php
session_start();
if (!isset($_SESSION['doctor_name'])) {
    header("Location: d_login.php");
    exit();
}

$doctorName = $_SESSION['doctor_name'];

// Database connection
$host = "localhost";
$dbname = "healthsync";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch clinic visit data
$sql = "SELECT c_name, c_location, time FROM work_on WHERE d_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctorName);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Visits</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 16px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            text-align: left;
        }

        th {
            background-color: rgba(255, 255, 255, 0.1);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Clinic Visits - Dr. <?php echo htmlspecialchars($doctorName); ?></h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Clinic Name</th>
                        <th>Location</th>
                        <th>Visit Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['c_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['c_location']); ?></td>
                            <td><?php echo htmlspecialchars($row['time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No clinic visits found.</p>
        <?php endif; ?>

        <a href="d_dash.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
