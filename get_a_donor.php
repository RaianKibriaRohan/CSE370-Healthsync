<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Search for Donors</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(45deg, #ff6b6b, #ffcc5c, #6bff6b, #4c6bff);
      background-size: 400% 400%;
      animation: gradient 10s ease infinite;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      text-align: center;
      flex-direction: column;
    }

    @keyframes gradient {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }

    h1 {
      font-size: 40px;
      color: white;
      font-weight: bold;
      margin-bottom: 30px;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .form-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 15px;
      width: 450px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease-in-out;
    }

    .form-container:hover {
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .form-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      background-color: #f9f9f9;
      transition: all 0.3s ease;
    }

    .form-container input:focus {
      border-color: #4CAF50;
      background-color: #e8f5e9;
    }

    .form-container button {
      width: 100%;
      padding: 14px;
      background: linear-gradient(45deg, #ff6b6b, #ffcc5c);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 18px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .form-container button:hover {
      background: linear-gradient(45deg, #ffb74d, #ff6b6b);
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .form-container label {
      font-size: 18px;
      color: #333;
      text-align: left;
      margin-bottom: 8px;
      display: block;
    }
  </style>
</head>
<body>

  <h1>Search for Donors</h1>

  <div class="form-container">
    <form action="submit_search.php" method="POST">
      <label for="location">Location:</label>
      <input type="text" id="location" name="location" required placeholder="Enter your location">

      <label for="blood_group">Blood Group:</label>
      <input type="text" id="blood_group" name="blood_group" required placeholder="e.g. A+, B-, O+">

      <button type="submit">Search</button>
    </form>
  </div>

</body>
</html>
