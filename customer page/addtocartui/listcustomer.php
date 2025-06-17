<?php
// Connect to the database
$dbc = mysqli_connect("localhost", "root", "", "acrylic");

if (mysqli_connect_errno()) {
    echo "<p style='color:red;'>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>";
    exit();
}


$sql = "SELECT * FROM user";
$result = mysqli_query($dbc, $sql);

// Display table
echo '<table>
<tr>
  <th>Customer ID</th>
  <th>Name</th>
  <th colspan="2">Action</th>
</tr>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>
      <td>' . htmlspecialchars($row['userid']) . '</td>
      <td>' . htmlspecialchars($row['username']) . '</td>
      <td><a href="userupdate.php?fcustid=' . urlencode($row['userid']) . '" class="btn btn-warning">Update</a></td>
      <td><a href="userdelete.php?fid=' . urlencode($row['userid']) . '" class="btn btn-danger">Delete</a></td>
    </tr>';
}

echo '</table>';

// Close connection
mysqli_close($dbc);
?>
