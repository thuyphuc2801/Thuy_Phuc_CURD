<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once "./user.php";
include_once __DIR__ . "/validate.php";
$errors = [];

if (isset($_POST['sign-in'])) {
    $errors = validate($_POST, ['email', 'password']);
    if (count($errors) == 0) {
        $data = [
            'email' => $_POST['email'],
            'password' => md5($_POST['password']), // ma hoa mat khau cua user nhap de so sanh voi db
        ];

        $user = User::signIn($data); // Goi ham signIn de lay info cua user voi email va password nhu nguoi dung nhap form
        if (!empty($user)) { // Neu $user khong rong
            $_SESSION['message'] = "Hello, " . $user['name'];
            $errors = [];
            header("location:index.php");
        } else { // Neu $user rong thi thong bao loi
            $_SESSION['message'] = "Sign in failure. Email or password incorrect!";
        }
    }
} else {
    $_SESSION['message'] = "";
    $errors = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Sign in </title>
</head>

<body>
    <div class="container">
        <div>
            <h1 class="text-center"> Sign in </h1>
        </div>
        <div class="row justify-content-md-center">
            <?php if (!empty($_SESSION['message'])) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <p>
                        <?php echo ($_SESSION['message']);
                        unset($_SESSION['message']) ?>
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            <div class="col-sm-6">
                <form class="row g-3 needs-validation" method="POST">
                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Email</label>

                        <input type="text" placeholder="Nhập email của bạn" class="form-control" name="email" id="validationCustom01" required>
                        <div class="valid-feedback">
                            Email is required
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Password</label>
                        <input type="text" placeholder="Nhập password của bạn" class="form-control" name="password" id="validationCustom01" required>
                        <div class="valid-feedback">
                            Password is required
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-primary" type="submit" name="sign-in">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>