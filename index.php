<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include_once "./user.php";
$users = User::all();

// Trang hiện tại, mặc định là trang 1 nếu không có trang nào được chỉ định.
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$users = [];
// VÌ THAY ĐỔI POST -> GET Ở DÒNG 60 NÊN ĐỔI CÁCH LẤY DỮ LIỆU $_POST THÀNH $_GET
if (isset($_GET['keyword'])) { // THAY ĐỔI THÀNH KIỂM TRA BIẾN KEYWORD
    $users = User::searchByName($_GET['keyword'], $page); // TRUYEN THEM PARAM $page ĐỂ PHÂN TRANG LÚC SEARCH
    $totalUsers = User::countByName($_GET['keyword']); // Ví dụ: tổng số mục dữ liệu là 20 users có tên trùng keyword.
} else {
    $users = User::pagination($page); // THAY DOI HAM all() THANH HAM pagination()
    // header("location:./index.php");
    $totalUsers = User::CountUser(); // Ví dụ: tổng số mục dữ liệu là 100.
}

// Tính tổng số trang dựa trên tổng số mục và số mục trên mỗi trang
$totalPages = ceil($totalUsers / User::USER_PER_PAGE);

// Xác định vị trí bắt đầu của trang hiện tại
$offset = ($page - 1) * User::USER_PER_PAGE;
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title> My Web </title>
</head>

<body>

    <div class="container">
        <div>
            <h1> User List </h1>
        </div>
        <?php if (isset($_SESSION['message'])) { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <p>
                    <?php echo ($_SESSION['message']);
                    unset($_SESSION['message']) ?>
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <!-- THÊM NÚT ALL OF LIST ĐỂ XEM TOÀN BỘ USERS -->
        <a href="index.php" class="btn btn-success"> All of list </a>
        <a href="create.php" class="btn btn-primary"> Create </a>

        <!-- THAY DOI PHUONG THUC POST THANH GET DE LUC SEARCH CUNG CO THE PHAN TRANG -->
        <form method="GET" class="mt-4"> 
            <div class="input-group mb-3">
                <input type="text" placeholder="Nhập tên để tìm kiếm" class="form-control" name="keyword" required>
                <!-- BỎ NAME=SEARCH -->
                <button type="submit" class="btn btn-success"> Search </button>
            </div>
        </form>

        <div>
            <?php if (count($users) > 0) { ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                                <th scope="row"><?= $user['id'] ?> </th>
                                <td><?php echo $user['name'] ?></td>
                                <td><?php echo $user['email'] ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="show.php?id=<?= $user['id'] ?>" class="btn btn-info mx-1">Show</a>
                                        <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-warning mx-2">Edit</a>
                                        <form action="delete.php" method="post" id="formDelete-<?= $user['id'] ?>">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <button type="button" class="btn btn-delete btn-danger" id="<?= $user['id'] ?>">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php }  ?>

                    </tbody>
                </table>
            <?php } else { ?>
                <h2> No Data. </h2>
            <?php }  ?>

        </div>
        <!-- Hiển thị dữ liệu -->
        <div class="col-md-12">
            <!-- Hiển thị danh sách dữ liệu -->
            <!-- <ul>
                <?php foreach ($data as $item) { ?>
                    <li> <?php echo $item; ?></li>
                <?php } ?>
            </ul> -->

            <!-- Hiển thị phân trang -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php 
                        if ($page > 1) {
                           // TẠO URL BAN ĐẦU
                           $url = "?page=" . ($page - 1);
                           // NẾU ĐANG SEARCH THÌ THÊM PARAM search và keyword VÀO ĐƯỜNG DẪN
                           if (isset($_GET['keyword'])) {
                               $url = $url . '&keyword='.$_GET['keyword'];
                           } 
                    ?>
                        <li class="page-item">
                            <!-- GÁN ĐƯỜNG DẪN MỚI TẠO VÀO HREF -->
                            <a class="page-link" href="<?php echo ($url); ?>" aria-label="Previous">
                                <span aria-hidden="true">Previous</span>
                            </a>
                        </li>
                    <?php } ?>

                    <?php 
                        for ($i = 1; $i <= $totalPages; $i++) {
                            // TẠO URL BAN ĐẦU
                            $url = "?page=" . $i;
                            // NẾU ĐANG SEARCH THÌ THÊM PARAM search và keyword VÀO ĐƯỜNG DẪN
                            if (isset($_GET['keyword'])) {
                                $url = $url . '&keyword='.$_GET['keyword'];
                            }
                    ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <!-- GÁN ĐƯỜNG DẪN MỚI TẠO VÀO HREF -->
                                <a class="page-link" href="<?php echo $url; ?>"><?php echo $i; ?></a>
                            </li>
                    <?php } ?>

                    <?php 
                        if ($page < $totalPages) {
                            // TẠO URL BAN ĐẦU
                            $url = "?page=" . ($page + 1);
                            // NẾU ĐANG SEARCH THÌ THÊM PARAM search và keyword VÀO ĐƯỜNG DẪN
                            if (isset($_GET['keyword'])) {
                                $url = $url . '&keyword='.$_GET['keyword'];
                            }
                     ?>
                        <li class="page-item">
                            <!-- GÁN ĐƯỜNG DẪN MỚI TẠO VÀO HREF -->
                            <a class="page-link" href="<?php echo ($url); ?>" aria-label="Next"> 
                                <span aria-hidden="true">Next</span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        let deleteBtns = document.querySelectorAll('.btn-delete');
        deleteBtns.forEach(function(item) {
            item.addEventListener('click', function(event) {
                if (confirm("Do you want to delete user?")) {
                    let id = this.getAttribute('id');
                    document.querySelector('#formDelete-' + id).submit();
                }
            })
        })
    </script>

</body>

</html>
