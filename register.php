<?php
    require 'php/vendor/autoload.php';
    use Google\Cloud\BigQuery\BigQueryClient;
    ?>
<?php
    $text= $username= $pass= $credentials= "";
    if(isset($_POST["submit"])){

        $username=($_POST["username"]);
        $password=($_POST["pass"]);
         $role=($_POST["role"]);
            if($role=="Admin" || $role=="Customer"){
                $projectId='cloudlab3-249301';
                $datasetId='Testing';
                $bigQuery = new BigQueryClient([
                                               'projectId' => $projectId,
                                               ]);
                $dataset = $bigQuery->dataset($datasetId);
                
                $query="SELECT max(user_id) as user_id FROM `Testing.user_credential`";
                
                $queryJobConfig = $bigQuery->query($query);
                $queryResults = $bigQuery->runQuery($queryJobConfig);
                if($queryResults->isComplete())
                {
                    foreach ($queryResults as $maxuserid)
                    $userid=$maxuserid['user_id']+1;
                }
                else
                {
                    $userid=1;
                }
                
                $sql="INSERT INTO `user_credential` (`user_id`,`user`, `password`,`role`) VALUES ($userid,'$username','$password','$role')";
                
                $queryConfig = $bigQuery->query($sql)->defaultDataset($dataset);
                $response=$bigQuery->runQuery($queryConfig);
                
                if($response->isComplete())
                {
                    header("location:login.php");
                }
            }
            else
                echo "Role has to be either Admin or Customer!"."\n"." Please check your spelling and retry";
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

.register-container{
    background-color: #555;
color: black;
border: none;
cursor: pointer;
opacity: 0.8;
position: fixed;
bottom: 50%;
right: 50%;
width: 50%;
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
<div class="register-container">
<form action="" method="post" class="form-container">
Username: <input type="text" name="username" placeholder="Enter Username" required> <br>
Role : <input type="text" name="role" placeholder="Admin or Customer?"> <br>
Password: <input type="password" name="pass"> <br>
<button type="submit" style="height : 100 px; width : 100px;" name="submit">Register</button>
</form>
</div>
</body>
</html>

