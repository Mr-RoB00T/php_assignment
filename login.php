<?php

// Start session
session_start();

// Include db connection
require 'database.php';

// Check form submit
if($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Get form data
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Validate input
  if(empty($email) || empty($password)) {
    $error = 'Please enter your email and password.';
  } else {

    // Escape inputs
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Get user from db
    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if($user = mysqli_fetch_assoc($result)) {

      // Verify password
      if(password_verify($password, $user['password'])) {

        // Passed, log user in
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;

      } else {
        $error = 'Incorrect password.';
      }

    } else {
      $error = 'Email not registered.';
    }

  }

}

?>

<?php if(!empty($error)): ?>
  <p><?php echo $error; ?></p>
<?php endif; ?> 

<h1>Login</h1>

<form method="post">
  
  <input type="email" name="email">

  <input type="password" name="password">

  <button type="submit">Login</button>
  
</form>