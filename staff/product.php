<?php
$pname = $_POST['product_name'];
$pprice = $_POST['product_price'];
$pquantity = $_POST['product_quantity'];
$ptype = $_POST['product_type'];
$pdesign = $_POST['product_design'];
$font = $_POST['product_font'];

if (isset($_POST['btnsubmit'])) {
    if (empty($pname) || empty($pprice) || empty($pquantity) ||
        empty($ptype) || empty($pdesign) || empty($font)) {
        echo '<script>alert("All fields are required.");</script>';
        echo '<script>window.location.assign("product.php");</script>';
        exit();
    }
    $dbc = mysqli_connect("localhost", "root", "", "acrylic");

    if (mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_error();
        exit();
    }

    $sql = "INSERT INTO product (product_name, product_price, product_quantity, product_type, product_design, product_font) 
            VALUES ('$pname', '$pprice', '$pquantity', '$ptype', '$pdesign', '$font')";

    $results = mysqli_query($dbc, $sql);

    if ($results) {
        echo '<script>alert("Record Has Been Added");</script>';
        echo '<script>window.location.assign("product.php");</script>';
    } else {
        echo '<script>alert("Data Is Invalid, No Record Has Been Added");</script>';
        echo '<script>window.location.assign("product.php");</script>';
    }

    mysqli_close($dbc);
}
?>