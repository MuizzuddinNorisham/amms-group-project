<?php
// Database connection
$host = 'localhost';
$db   = 'acrylic';      // Replace with your DB name
$user = 'root';      // Replace with your DB user
$pass = '';      // Replace with your DB password

$conn = new mysqli($host, $user, $pass, $db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Check if image_name is set
    if (!isset($_POST['image_name'])) {
        die("Error: Missing image name.");
    }

    $name = $conn->real_escape_string($_POST['image_name']);

    // Check if file was uploaded without errors
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image_file']['tmp_name'];
        $imageData = file_get_contents($tmp_name);
        $imageData = $conn->real_escape_string($imageData);

        $sql = "INSERT INTO image (image_name, image_data) VALUES ('$name', '$imageData')";

        if ($conn->query($sql) === TRUE) {
            echo "Image uploaded successfully.";
        } else {
            echo "Error uploading image: " . $conn->error;
        }
    } else {
        echo "Error uploading file. Code: " . $_FILES['image_file']['error'];
    }
}

$conn->close();
?>

<!-- upload.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Upload Image</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <!-- Must match $_POST['image_name'] -->
        <input type="text" name="image_name" placeholder="Image Name" required />
        
        <!-- Must match $_FILES['image_file'] -->
        <input type="file" name="image_file" accept="image/*" required />
        
        <input type="submit" value="Upload Image" />
    </form>
</body>
</html>