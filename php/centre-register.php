<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "mydb";

$connection = new mysqli($servername, $username, $password, $database);

$centre="";
$name="";
$address="";
$date="";
$status="slot-booked";
$totcount = 0;

$errormessage = "";
$successmessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $centre = $_POST["centre"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $date=  date('Y-m-d',strtotime($_POST["date"]));
    $totcount = 0;

    // Check if all fields are filled
    if (empty($centre) || empty($name) || empty($address)) {
        $errormessage = "All the fields are required";
    } else {
        // Check if there are already 10 candidates for the day
        $sql = "SELECT * FROM centre WHERE name='$centre'";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["totcount"] >= 10) {
                $errormessage = "No more slots available for $centre today";
            } else {
                // Increment the count and update the database
                $sql1 = "UPDATE centre SET totcount = totcount + 1 WHERE name = '$centre';";
                $result1 = $connection->query($sql1);

                $sql = "INSERT INTO details (centre,name,address,date,status) VALUES ('$centre','$name','$address','$date','$status')";
                $result = $connection->query($sql);
        

                if (!$result1) {
                    $errormessage = "Invalid query" . $connection->error;
                } else {
                    // Reset the form and show success message
                    $name = "";
                    $centre="";
                    $address="";
                    $date="";
                    $successmessage = "Slot booked successfully !!";
                }
            }
        } else {
           
                $errormessage = "No such centre exists. Type it with case-sensitive" . $connection->error;

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Slot booking</title>
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background-color: #F8F9FA;
            border: 1px solid #CED4DA;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
            width: 50%;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box">
            <h2 class="mb-5">Book slot by entering the details</h2>

            <?php 
            if(!empty($errormessage)){
                echo"
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errormessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>X</button>
                </div>
                ";
            }
            ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Vaccination centre</label>
                    <input type="text" class="form-control" name="centre" value="<?php echo $centre;  ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Your name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $name;  ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Date of booking</label>
                    <input type="date" class="form-control" name="date" value="<?php echo $date;  ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" value="<?php echo $address;  ?>">
                </div>

                <?php 
            if(!empty($successmessage)){
                echo"
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successmessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'>X</button>
                </div>
                ";
            }
            ?>

                <div class="row">
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <a class="btn btn-outline-primary" href="/php/index.php">Cancel</a>
                </div>
                </div>

            </form>
        </div>
    </div>
</body>
</html>
