<?php
    if(isset($_SESSION['username']))
    {
        unset($_SESSION['username']);
        unset($_SESSION['userid']);
    }
    require 'php/vendor/autoload.php';
    use Google\Cloud\BigQuery\BigQueryClient;
    ?>
<?php
    session_start();
    $userid = $password = "";
    if(isset($_POST["username"]) && isset($_POST["password"]))
    {
        $username=($_POST["username"]);
        $password=($_POST["password"]);
        $projectId='cloudlab3-249301';
        $bigQuery = new BigQueryClient([
                                       'projectId' => $projectId,
                                       ]);
    
        $str = '';
        
        $query="SELECT user,password,role,user_id FROM `Testing.user_credential` where user='$username' and password='$password' LIMIT 10";
        
        $queryJobConfig = $bigQuery->query($query);
        $queryResults = $bigQuery->runQuery($queryJobConfig);
        if($queryResults->isComplete())
        {
            foreach ($queryResults as $row)
            {
                if($row['role']=="Admin")
                {
                    $_SESSION['username']=$username;
                    $_SESSION['role']="Admin";
                    $_SESSION['userid']=$row['user_id'];
                    header("location:main.php");
                }
                else
                    if($row['role']=="Customer")
                    {
                        $_SESSION['username']=$username;
                        $_SESSION['role']="Admin";
                        $_SESSION['userid']=$row['user_id'];
                        header("location:customer.php");
                    }
            }
        }
            echo "Invalid Username or Password";
    }
    ?>
<html>
<body>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

body {
    background-image: url("https://storage.cloud.google.com/s3811346-storage/cloudlab3/realistic-coffee-background-with-drawings_79603-607.jpg");
width: 100%;
    background-color: #cccccc;
    }

.login-button{
background-color: #555;
color: black;
border: none;
cursor: pointer;
opacity: 0.8;
position: fixed;
bottom: 50%;
right: 50%;
left: 50%;
width: 280px;
top: 50%;
}

.form-container {
    max-width: 300px;
padding: 10px;
    background-color: white;
bottom: 100%;
right: 100%;
left: 100%;
width: 280px;
top: 100%;
    
}
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

</style>
<div class="login-button">
<form action="" class="form-container" method="post">
Username:<input type="text" name="username" placeholder="Username" required> <br> </br>
Password:<input type="password" name="password" placeholder="Password" required> <br>
<button type="submit" class="btn">Log in</button>
<a href="register.php">Register</a>
</form>
</div>
</body>
</html>
