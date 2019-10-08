<?php
    session_start();
    require 'php/vendor/autoload.php';
    use Google\Cloud\BigQuery\BigQueryClient;
?>
<html>
<body>
<link type="text/css" rel="stylesheet" href="/bootstrap/css/bootstrap.css">
<link type="text/css" rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

body {
    background-image: url("https://storage.cloud.google.com/s3811346-storage/cloudlab3/realistic-coffee-background-with-drawings_79603-607.jpg");
width: 100%;
    background-color: #cccccc;
}
/* Button used to open the contact form - fixed at the bottom of the page */
.open-button {
    background-color: #555;
color: white;
padding: 16px 20px;
border: none;
cursor: pointer;
opacity: 0.8;
position: fixed;
top: 23px;
right: 28px;
width: 280px;
}

/* The popup form - hidden by default */
.form-popup {
display: none;
position: fixed;
top: 0;
right: 15px;
border: 3px solid #f1f1f1;
    z-index: 9;
}

/* Add styles to the form container */
.form-container {
    max-width: 300px;
padding: 10px;
    background-color: white;
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
width: 100%;
padding: 15px;
margin: 5px 0 22px 0;
border: none;
background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
    background-color: #ddd;
outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
    background-color: #4CAF50;
color: white;
padding: 16px 20px;
border: none;
cursor: pointer;
width: 100%;
    margin-bottom:10px;
opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
    background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
opacity: 1;
}
</style>
<?php
    if(isset($_SESSION['username']))
    echo "<div> Logged in as ".$_SESSION['username']." ".$_SESSION['userid']."</div>";
    if(isset($_POST['firstname'])){
        $firstname=($_POST["firstname"]);
        $phone_no=($_POST["phone_no"]);
        $email=($_POST["email"]);
        $userid=($_SESSION['userid']);
        
        if(!empty($_POST["lastname"]))
            $lastname=($_POST["lastname"]);
        else
            $lastname=" ";
        $projectId='cloudlab3-249301';
        $datasetId='Testing';
        $bigQuery = new BigQueryClient([
                                       'projectId' => $projectId,
                                       ]);
        $dataset = $bigQuery->dataset($datasetId);
        $sql="INSERT INTO `customer_info` (`user_id`,`first_name`,`last_name`,`phone_no`,`email`) VALUES ($userid,'$firstname','$lastname',$phone_no,'$email')";
        
        $queryConfig = $bigQuery->query($sql)->defaultDataset($dataset);
        $response=$bigQuery->runQuery($queryConfig);
        
        if($response->isComplete())
        {
            echo "Your Details have been saved ".$firstname." ".$lastname;
        }
}
    
    ?>
<h1>Main Content For Customer</h1>
<form action="/login.php" method="post">
<button type="submit" style="height: 25px; width : 100px;">Logout</button>
</form>
<div class="container">
<button class="open-button" onclick="openForm()">Create Customer Profile</button>
<div class="form-popup" id="myForm">
<form action="" class="form-container" method="post">
First name : <input type="text" name="firstname" required> <br>
Last name : <input type="text" name="lastname"> <br>
Phone number : <input type="text" name="phone_no" required> <br>
Email : <input type="email" name="email" required> <br>
<button type="submit" class="btn">Create</button>
<button type="button" class="btn cancel" onclick="closeForm()">Close</button>
</form>
<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>
</div>
</div>
</body>
</html>

