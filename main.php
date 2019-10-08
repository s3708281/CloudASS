<?php
    session_start();
    require 'php/vendor/autoload.php';
    use Google\Cloud\Datastore\DatastoreClient;
    ?>
<html>
<link type="text/css" rel="stylesheet" href="/bootstrap/css/bootstrap.css">
<link type="text/css" rel="stylesheet" href="/bootstrap/css/bootstrap-responsive.css">
<style type="text/css">
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

body {
    background-image: url("https://storage.cloud.google.com/s3811346-storage/cloudlab3/realistic-coffee-background-with-drawings_79603-607.jpg");
width: 100%;
    background-color: #cccccc;
}
blockquote {
    margin-bottom: 10px;
    border-left-color: #bbb;
}
form {
    margin-top: 10px;
}
.form-signin input[type="text"] {
    font-size: 16px;
height: auto;
    margin-bottom: 15px;
padding: 7px 9px;
}
.row {
    margin-left: 0px;
    margin-top: 10px;
overflow: scroll;
}
</style>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<div class="container">
<span class="icon-bar"></span>
</button>
<div class="nav-collapse collapse pull-right">
</div>
</div>
</div>
</div>
<div class="container">
<body>
<?php
    if(isset($_SESSION['username']))
    echo "<div> Logged in as ".$_SESSION['username']."</div>";
    $projectId='cloudlab3-249301';
    
    $file= fopen('gs://s3811346-storage/prime_numbers.txt','r');
    $read=fread($file,filesize('gs://s3811346-storage/prime_numbers.txt'));
    $string1="<header>".$read."</header>";
    echo $string1;
    echo "<br>";
    if(!isset($_POST['comment']))
    {
        $datastore = new DatastoreClient(['projectId' => $projectId]);
        $taskKey = $datastore->key('Comment');
        $query = $datastore->query()->kind('Comment')->order('date', 'DESCENDING')->limit(5);
        $results = $datastore->runQuery($query);
        $visits = [];
        foreach ($results as $entity) {
            echo "<label>Comment: </label>"." ".$entity['comment']."<br>"."<label>User</label>"." ".$entity['name']."<br>";
        }
    }

    if(isset($_POST['comment']))
    {
        $comment=$_POST['comment'];
        $datastore = new DatastoreClient(['projectId' => $projectId]);
        $taskKey = $datastore->key('Comment');
        $task = $datastore->entity(
                               $taskKey,
                               [
                               'name' => $_SESSION['userid'],
                               'comment' => $comment,
                                'date' => new DateTime()
                               ]);
        $datastore->insert($task);
        $query = $datastore->query()->kind('Comment')->order('date', 'DESCENDING')->limit(6);
        $results = $datastore->runQuery($query);
        $visits = [];
        foreach ($results as $entity) {
            echo "<label>Comment: </label>"." ".$entity['comment']."<br>"."<label>User</label>"." ".$entity['name']."<br>";
        }
        
    }
    ?>
<h1>Main Content For Admin</h1>
<form action="/login.php" method="post">
<button type="submit" style="height: 25px; width : 100px;">Logout</button>
</form>
<img src='https://storage.cloud.google.com/s3811346-storage/cloudlab3/10.jpg' alt='GoogleStorage Image'>
<form action="" method="post">
<input type="text" name="comment">
<button type="submit">Add Comment</button>
</form>
<form action="/pubsub.php" method="post">
<button type="submit">PubSub</button>
</form>

</body>
</html>

