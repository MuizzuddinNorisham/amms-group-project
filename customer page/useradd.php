<?php
$uname = $_POST['username'];
$uemail = $_POST['useremail'];
$upass = $_POST['userpass'];

if (isset($_POST['add'])) {
    $dbc = mysqli_connect("localhost", "root", "", "acrylic");

    if (mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_error();
        exit();
    }

    // Insert without userid since it auto-generates
    $sql = "INSERT INTO `user` (`username`, `useremail`, `userpass`) 
            VALUES ('$uname', '$uemail', '$upass')";

    $results = mysqli_query($dbc, $sql);

    if ($results) {
        mysqli_commit($dbc);
        echo '<script>alert("Record Has Been Added");</script>';
        echo '<script>window.location.assign("listcustomer.php");</script>';
    } else {
        mysqli_rollback($dbc);
        echo '<script>alert("Data Is Invalid, No Record Has Been Added");</script>';
        echo '<script>window.location.assign("listcustomer.php");</script>';
    }

    mysqli_close($dbc);
}
?>
