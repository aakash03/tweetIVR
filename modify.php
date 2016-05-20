<?php
$servername = "";
$username = "";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password,'tweetivr');


echo "Updated successfully";
  $number = $_POST['number'];
  $uname = $_POST['username'];
$result = mysqli_query($conn,"UPDATE tweetivr SET username='$uname' where  number='$number'");
mysqli_close($conn);

  ?>