<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Choose Role</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .top-right {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .donor-btn {
      background-color: #ff6f61;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .donor-btn:hover {
      background-color: #e85b50;
    }

    .signup-button {
      display: block;
      margin-top: 30px;
      text-align: center;
    }

    .signup-button a {
      background-color: #4CAF50;
      color: white;
      padding: 10px 25px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .signup-button a:hover {
      background-color: #43a047;
    }
  </style>
</head>
<body>
  <!-- Donor + Admin Buttons -->
  <div class="top-right">
    <a href="donor.php">
      <button class="donor-btn">Become a Donor</button>
    </a>
    <a href="admin.php">
      <button class="donor-btn" style="background-color: #2196F3; margin-left: 10px;">Admin</button>
    </a>
  </div>

  <div class="container">
    <h1>Who are you?</h1>
    <div class="button-group">
      <a href="d_login.php" class="role-button doctor">Doctor</a>
      <a href="p_login.php" class="role-button patient">Patient</a>
    </div>

    <!-- Sign Up Button -->
    <div class="signup-button">
      <a href="signup.php">Sign Up</a>
    </div>
  </div>
</body>
</html>
