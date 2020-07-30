<?php

session_start();
if(!isset($_SESSION['loggedin']) or $_SESSION['loggedin'] != true){
    header("location: login.php");
    exit(); 
}

$email = $_SESSION['email'];


$isCreated = false;
$isUpdated = false;
$isDeleted = false;

// Connecting to database 

$server = "localhost";
$username = "root";
$password = "";
$database = "users_data";


$connect = mysqli_connect($server, $username, $password, $database);

if (!$connect) {
    echo "Something went wrong!";
}


if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];

    $query = "delete from notes where sno=" . $sno;
    $result = mysqli_query($connect, $query);

    if ($result) {
        $isDeleted = true;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['snoEdit'])) {
        $sno = $_POST['snoEdit'];
        $title = $_POST['titleEdit'];
        $description = $_POST['descriptionEdit'];

        $query = "update notes set title='" . $title . "', description='" . $description . "' where sno=" . $sno;

        $result = mysqli_query($connect, $query);

        if ($result) {
            $isUpdated = true;
        } else {
            echo "unable to update";
        }
    } else {
        $title = $_POST['title'];
        $description = $_POST['description'];

        $query = "insert into notes ( email, title, description ) values ( '$email', '$title' , '$description' )";

        $result = mysqli_query($connect, $query);

        if ($result) {
            $isCreated = true;
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
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

    <title>Welcome <?php echo $_SESSION['username']; ?></title>

</head>

<body>

    <!-- <button type = "button" class="btn btn-primary" data-toggle="modal" data-target="#editModal"> click me! </button> -->

    <!-- Modal -->

    <div class="modal fade" tabindex="-1" id="editModal" role="dialog" aria-labelledby="editModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit this note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="/mynotes/notes.php">
                    <div class="modal-body">

                        <input type="hidden" id="snoEdit" name="snoEdit">
                        <div class="form-group">
                            <label for="title">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" aria-describedby="title" name="titleEdit">
                        </div>
                        <div class="form-group">
                            <label for="decription">Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">My Notes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
        </div>
    </nav>


    <?php
    if ($isCreated) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> You have successfully added your note.
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>";
    }
    if ($isUpdated) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> You have successfully Updated the note.
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>";
    }
    if ($isDeleted) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> You have successfully Deleted the note.
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>";
    }

    ?>

    <!-- Form for adding Notes -->

    <div class="container my-4">
        <form method="post" action="/MyNotes/notes.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" aria-describedby="title" name="title">
            </div>
            <div class="form-group">
                <label for="decription">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Tables to show User Notes -->


    <div class="container">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Sno</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

                <?php

                $query = "select * from notes where email = '$email'";
                $result = mysqli_query($connect, $query);
                $sno = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    echo " <tr>
                    <th scope='row'>" . $sno . " </th>
                    <td> " . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td> <button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button> <button id=d" . $row['sno'] . " class='delete btn btn-sm btn-primary'>Delete</button> </td>
                </tr>";

                    $sno = $sno + 1;
                }

                ?>
            </tbody>
        </table>
    </div>

    <br>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                // console.log("edit ", e.target.parentNode.parentNode);
                $parent = e.target.parentNode.parentNode;
                $title = $parent.getElementsByTagName('td')[0].innerText;
                $description = $parent.getElementsByTagName('td')[1].innerText;

                console.log($title, $description);

                descriptionEdit.value = $description;
                titleEdit.value = $title;

                snoEdit.value = e.target.id;

                $('#editModal').modal('toggle')


            })
        })

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                sno = e.target.id.substr(1, );
                console.log("sno  = ", sno);

                if (confirm("Do you want to Delete this note")) {
                    console.log("yes");
                    window.location = '/mynotes/notes.php?delete=' + sno;
                } else {
                    console.log("no");
                }
            })
        })
    </script>

</body>

</html>