<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
.error {color: #FF0000;}
</style>
</head>

<body>

  <?php
  // define variables and set to empty values
  $nameErr = $emailErr = $genderErr = $websiteErr = $pwErr = $cfpwErr = "";
  $name = $email = $gender = $comment = $website = $password = $cfpassword = "";
  $showOutput = false;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $showOutput = true;

    if (empty($_POST["name"])) {
      $nameErr = "Name is required";
      $showOutput = false;
    } else {
         
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameErr = "Only letters and white space allowed";
        $showOutput = false;
          }
      else
         $name = test_input($_POST["name"]);
    }

    if (empty($_POST["email"])) {
      $emailErr = "Email is required";
      $showOutput = false;
    } else {
      $email = test_input($_POST["email"]);

      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $showOutput = false;
      }
      else
        $email = test_input($_POST["email"]);
    }

    // Password Validation
if (empty($_POST["password"])) {
  $pwErr = "Password is required";
  $showOutput = false;
} else {
  $password = test_input($_POST["password"]);
  if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@(!)!%*?&]).{8,}$/", $password)) {
    $pwErr = "Invalid Password";
    $showOutput = false;
  }
  else {
    $password = test_input($_POST["password"]);
  }
}

// Confirm Password Validation
if (empty($_POST["cfpassword"])) {
  $cfpwErr = "Confirm Password is required";
  $showOutput = false;
} else {
  $cfpassword = test_input($_POST["cfpassword"]);
  if ($password !== $cfpassword) {
    $cfpwErr = "Passwords do not match";
    $showOutput = false;
  } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@(!)!%*?&]).{8,}$/", $cfpassword)) {
    $cfpwErr = "Invalid Confirm Password";
    $showOutput = false;
  } else {
    $cfpassword = test_input($_POST["cfpassword"]);
  }
}

    if (empty($_POST["website"])) {
      $website = "";
    } else {
  
      // check if URL address syntax is valid
      if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
        $websiteErr = "Invalid URL";
        $showOutput=false;
      }
      else
            $website = test_input($_POST["website"]);
    }

    if (empty($_POST["comment"])) {
      $comment = "";
    } else {
      $comment = test_input($_POST["comment"]);
    }

    if (empty($_POST["gender"])) {
      $genderErr = "Gender is required";
      $showOutput=false;
    } else {
      $gender = test_input($_POST["gender"]);
    }
  }

  function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  ?>

  <h2>PHP Form Validation Example</h2>
  <p><span class="error">* required field</span></p>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    
    Name: <input type="text" name="name">
    <span class="error">* <?php echo $nameErr; ?></span>
    <br><br>

    E-mail: <input type="text" name="email">
    <span class="error">* <?php echo $emailErr; ?></span>
    <br><br>

    Password: <input type="password" name="password">
    <span class="error">* <?php echo $pwErr; ?></span>
    <br><br>

    Confirm Password: <input type="password" name="cfpassword">
    <span class="error">* <?php echo $cfpwErr; ?></span>
    <br><br>

    Website: <input type="text" name="website">
    <br><br>

    Comment: <textarea name="comment" rows="5" cols="40"></textarea>
    <br><br>

    Gender:
    <input type="radio" name="gender" value="female">Female
    <input type="radio" name="gender" value="male">Male
    <input type="radio" name="gender" value="other">Other
    <span class="error">* <?php echo $genderErr; ?></span>
    <br><br>

    <input type="submit" name="submit" value="Submit">
  
  </form>
<?php
if($showOutput)
{
echo "<h2>Your Input Data are :</h2>";
  echo "Name: ".$name;
  echo "<br>";
  echo "Email: ".$email;
  echo "<br>";
  echo "Password: ".$password;
  echo "<br>";
  echo "Confirm Password: ".$cfpassword;
  echo "<br>";
  echo "Website Address: ".$website;
  echo "<br>";
  echo "Your Comments: ".$comment;
  echo "<br>";
  echo "Your Gender: ".$gender;
}
 
  ?>

</body>

</html>