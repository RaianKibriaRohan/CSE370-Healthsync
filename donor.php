<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HealthSync - Blood Donation</title>
  <style>
    /* Basic styling for the body */
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      word-spacing: 1px;
      align-items: center;
      height: 100vh;
      background: linear-gradient(45deg, #ff6b6b, #ffcc5c, #6bff6b, #4c6bff);
      background-size: 400% 400%;
      animation: gradient 10s ease infinite;
      color: white;
      text-align: center;
      flex-direction: column; /* Stack logo and text vertically */
    }

    /* Gradient animation */
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

    /* Logo styling */
    .logo {
      width: 100px;  /* Adjust the size of the logo */
      margin-bottom: 20px;
    }

    h1 {
      font-size: 50px;
      color: white;
      font-weight: bold;
      margin-top: 10px;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    /* Dashboard container */
    .dashboard-container {
      display: flex;
      justify-content: space-evenly;
      margin-top: 50px;
      flex-wrap: wrap;
      gap: 20px;
      padding: 0 20px;
      max-width: 1200px;
      justify-content: center;
    }

    /* Segment styling */
    .segment {
      width: 400px;
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      overflow: hidden;
      text-align: center;
      opacity: 0;  /* Initially hidden */
      transform: translateY(50px); /* Initial position (below) */
      animation: popUp 1s forwards; /* Apply animation */
    }

    /* Animation for the smooth pop-up effect */
    @keyframes popUp {
      0% {
        opacity: 0;
        transform: translateY(50px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Animation for sliding in from the left and right */
    @keyframes slideLeft {
      0% {
        opacity: 0;
        transform: translateX(-100%);
      }
      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideRight {
      0% {
        opacity: 0;
        transform: translateX(100%);
      }
      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Apply the animations */
    .search-donor {
      animation: slideLeft 1s forwards; /* Slide in from the left */
      animation-delay: 0.2s; /* Slight delay */
    }

    .become-donor {
      animation: slideRight 1s forwards; /* Slide in from the right */
      animation-delay: 0.4s; /* Slight delay */
    }

    .segment h3 {
      font-size: 28px;
      margin: 20px 0;
      color: #333;
      font-weight: bold;
    }

    .segment p {
      color: #777;
      padding: 0 20px 20px;
      font-size: 16px;
    }

    /* Hover effect for the segments */
    .segment:hover {
      transform: translateY(-10px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* Style for the clickable text */
    .segment a {
      text-decoration: none;
      color: inherit;
    }

    /* Media query for responsiveness */
    @media (max-width: 768px) {
      .segment {
        width: 60%;
      }
    }

    @media (max-width: 480px) {
      .segment {
        width: 90%;
      }
    }
  </style>
</head>
<body>

  <!-- Logo image -->

  <h1>Blood Donation</h1>

  <div class="dashboard-container">
    <!-- Become Donor -->
    <div class="segment become-donor">
      <a href="become_a_donor.php">
        <h3>Become Donor</h3>
        <p>Sign up to become a registered blood donor and save lives.</p>
      </a>
    </div>

    <!-- Search Donor -->
    <div class="segment search-donor">
      <a href="src_donor.html">
        <h3>Search Donor</h3>
        <p>Find registered donors nearby for blood donation.</p>
      </a>
    </div>
  </div>

</body>
</html>