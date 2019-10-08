<?php
    $text= $username= $pass= $credentials= "";
    if(isset($_POST["submit"])){
        if(!empty($_POST['username']) && !empty($_POST['pass']) && !empty($_POST['role']))
        {
            $username=($_POST["username"]);
            $pass=($_POST["pass"]);
            $role=($_POST["role"]);
            $credentials=$username.",".$pass.",".$role;
            $myfile = fopen('gs://my_first_project_s3811346/users.txt', 'r') or die("Unable to open file!");
            $text=fread($myfile,filesize('gs://my_first_project_s3811346/users.txt'));
            fclose($myfile);
            $myfilew = fopen('gs://my_first_project_s3811346/users.txt','w');
            fwrite($myfilew,$text);
            fwrite($myfilew,"\n");
            fwrite($myfilew,$credentials);
            fclose($myfilew);
            header("location:login.php");
        }
        else
            echo "Username or Password cannot be empty!";
    }
    ?><html>
<body>
<form action="" method="post">
Username: <input type="text" name="username" placeholder="Enter Username"> <br>
Role : <input type="text" name="role" placeholder="Customer or Admin"> <br>
Password: <input type="password" name="pass"> <br>
<button type="submit" style="height : 100 px; width : 100px;" name="submit">Register</button>
</body>
</html>
