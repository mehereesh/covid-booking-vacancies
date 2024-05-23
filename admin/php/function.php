<?php
session_start();

// REDIS CONFIGURATION
// ini_set("session.save_handler", "redis");
// ini_set("session.save_path", "tcp://localhost:6379");

$conn = mysqli_connect("localhost", "root", "", "mydb");

// IF
if(isset($_POST["action"])){
  if($_POST["action"] == "register"){
    register();
  }
  else if($_POST["action"] == "login"){
    login();
  }
  else if($_POST["action"] == "update"){
    update();
  }
}

// UPDATE
function update(){

    global $conn;

    $stmt = $conn->prepare('UPDATE covid SET fullname=?, email=?, username=?, password=? WHERE username=?');

    // Bind the parameters
    $stmt->bind_param('ssss', $fullname, $email, $username, $password);

    // Execute the SQL statement
    if ($stmt->execute()) {
        echo 'User details updated successfully';
    } else {
        echo 'Error updating user details: ' . $stmt->error;
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
    exit;

}

// REGISTER
function register(){

  global $conn;

  $fullname = $_POST["fullname"];
  $username = $_POST["username"];
  $password = $_POST["password"];
  $email = $_POST["email"];

  if(empty($fullname) || empty($username) || empty($password)){
    echo "Please Fill Out The Form!";
    exit;
  }

  $user = mysqli_query($conn, "SELECT * FROM covid WHERE username = '$username'");
  if(mysqli_num_rows($user) > 0){
    echo "Username Has Already Taken";
    exit;
  }
  else{
    $stmt = $conn->prepare('INSERT INTO covid (fullname,email,username,password) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $fullname,$email,$username,$password);
    if($stmt->execute()){
      echo "Registration Successful";
    }
    else{
      echo "Error: Unable to register user";
    }
    
    $stmt->close();
    $conn->close();
  }
}


// LOGIN
function login(){
  global $conn;

  $username = $_POST["username"];
  $password = $_POST["password"];

  $user = mysqli_query($conn, "SELECT * FROM covid WHERE username = '$username'");

  if(mysqli_num_rows($user) > 0){

    $row = mysqli_fetch_assoc($user);

    if($password == $row['password']){
      echo "Login Successful";
      $_SESSION["login"] = true;
      $_SESSION["id"] = $row["id"];
    }
    else{
      echo "Wrong Password";
      exit;
    }
  }
  else{
    echo "User Not Registered";
    exit;
  }
}
?>