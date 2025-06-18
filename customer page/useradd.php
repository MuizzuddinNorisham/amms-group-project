<?php
$uname = $_POST['username'];
$uemail = $_POST['useremail'];
$upass = $_POST['userpass'];
$uphone = $_POST['userphone']; // Retrieve phone number
$uaddress = $_POST['useraddress']; // Retrieve address

if (isset($_POST['add'])) {
    if (empty($uname) || empty($uemail) || empty($upass) || empty($uphone) || empty($uaddress)) {
        echo '<script>alert("All fields are required.");</script>';
        echo '<script>window.location.assign("listcustomer.php");</script>';
        exit();
    }
    
    $dbc = mysqli_connect("localhost", "root", "", "acrylic");

    if (mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_error();
        exit();
    }

    // Insert without userid since it auto-generates
    $sql = "INSERT INTO `staff` (`staff_name`, `staff_phone`, `staff_address`, `staff_email`, `staff_pass`) 
            VALUES ('$uname', '$uphone', '$uaddress', '$uemail', '$upass')";

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
