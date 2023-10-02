<?php
include_once "./user.php";
session_start();
$id = null;
$user = null;

if ($_GET['id']) {
    $id = $_GET['id'];
    $user = user::find($id);
    // var_dump($user);

} else {
    header("location:/index.php");
}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title> Show user </title>
</head>

<body>
    <div class="container">
        <div>
            <h1> Show User </h1>
        </div>
        <?php if ($user) { ?>
            <h2> User Information </h2>

            <h3>Name: <?= $user['name'] ?></h3>
            <h3>Email: <?= $user['email'] ?></h3>

            <a href="index.php" class="btn btn-primary"> Back to List </a>
        <?php } else { ?>
            <h1> User Not Found. </h1>
            <a href="index.php" class="btn btn-primary"> Back to List </a>
        <?php } ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


</body>

</html>