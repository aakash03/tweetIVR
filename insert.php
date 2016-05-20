<?php
$servername = "";
$username = "";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password,'tweetivr');


echo "Registered successfully";
  $name = $_POST['name'];
  $email = $_POST['email'];
  $uname = $_POST['username'];
  $number = $_POST['number'];
$result = mysqli_query($conn,"INSERT INTO tweetivr (name,email,username,number) VALUES ('$name','$email','$uname','$number')");
mysqli_close($conn);

  ?>