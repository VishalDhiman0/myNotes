<?php

$isExists = false;
$showAlert = false;
$passwordMismatch = false;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Connection to Database;
    require_once('_dbconnect.php');

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $sql = "select * from users_info where email='$email'";
    $result = mysqli_query($connect, $sql);

    $num = mysqli_num_rows($result);

    if ($num > 0) {
        $isExists = true;
    } else if (!($password == $cpassword)) {
        $passwordMismatch = true;
    } else {
        $sql = "INSERT INTO `users_info` (`email`, `name`, `password`, `dt`) VALUES ('$email', '$username', '$password', current_timestamp());";
        // echo $sql;
        $result = mysqli_query($connect, $sql);

        // echo var_dump($result);

        if ($result) {
            $showAlert = true;
        }
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Register-MyNotes</title>
</head>

<body>

    <?php

    if ($passwordMismatch) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Ooops!</strong> Password Mismatch!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }

    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Congratulations! </strong> You have successfully registered with us! You can login now.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }

    if ($isExists) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Sorry! </strong> User Already Exists. Please! Use another account.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }

    ?>
    <h1 class="text-center my-4">Register With Us!</h1>
    <div class="container my-4">
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control " id="username" aria-describedby="username" name="username" placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control " id="email" name="email" aria-describedby="email" placeholder="Enter email">
                <small id="email" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control " id="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Confirm Password</label>
                <input type="password" class="form-control " id="cpassword" name="cpassword" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>