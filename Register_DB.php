<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        body {
            background: #f2f4f8;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: border 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        .btn {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .note {
            text-align: center;
            font-size: 0.9em;
            color: #777;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<?php
require_once("configMysql.php");

// Initialize variables (optional)
$name = $mail = $password= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $mail = htmlspecialchars(trim($_POST["mail"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    // Insert into DB
    $sql = "INSERT INTO user(name,mail,password)
            VALUES ('$name','$mail', '$address')";

    if (mysqli_query($conn, $sql)) {
        header('Location: index1.html');
        exit;
    } else {
        echo "<p style='text-align:center; color:red;'>Error saving data</p>";
    }
}
?>

<div class="form-container">
    <h2>Register New Customer</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">  
        <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="phno">Phone Number *</label>
            <input type="text" name="phno" id="phno" required>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" rows="4"></textarea>
        </div>

        <button class="btn" type="submit" name="submit">Register</button>
    </form>

    <p class="note">* All fields are required</p>
</div>

</body>
</html>
