<?php
include_once __DIR__ . "/DB.php";

class User
{
    const USER_PER_PAGE = 10;
    static public function all()
    {
        $sql = "select * from users";
        $users = DB::execute($sql);
        return $users;
    }

    static public function searchByName($keyword, $page) // THÊM PARAM $page ĐỂ PHÂN TRANG LÚC SEARCH
    {
        $offset = ($page - 1) * self::USER_PER_PAGE; // Vị trí bắt đầu của trang hiện tại
        $sql = "SELECT * FROM users WHERE name LIKE '%$keyword%' LIMIT $offset, ". self::USER_PER_PAGE; // THÊM LIMIT ĐỂ PHÂN TRANG
        $users = DB::execute($sql);
        return $users;
    }

    static public function countByName($keyword) // THÊM HÀM ĐẾM SỐ LƯỢNG USER CÓ TÊN GIỐNG KEYWORD
    {
        $sql = "SELECT count(id) AS count FROM users WHERE name LIKE '%$keyword%'"; // THÊM LIMIT ĐỂ PHÂN TRANG
        $result = DB::execute($sql);
        return $result[0]['count'];
    }

    static public function CountUser()
    {
        $sql = "SELECT count(id) AS count FROM users";
        $result = DB::execute($sql);
        return $result[0]['count'];
    }

    static public function create($dataCreate)
    {
        $sql = "INSERT INTO users (name, email, password) values (:name, :email, :password)";
        DB::execute($sql, $dataCreate);
    }
    static public function find($id)
    {
        $sql = "select * from users where id=:id";
        $dataFind = ['id' => $id];
        $user = DB::execute($sql, $dataFind);
        return count($user) > 0 ? $user[0] : [];
    }

    static public function update($dataUpdate)
    {
        $sql = "UPDATE users set name=:name, email=:email, password=:password where id=:id";
        DB::execute($sql, $dataUpdate);
    }

    static public function destroy($id)
    {
        $sql = "DELETE FROM users WHERE id=:id";
        $dataDelete = ['id' => $id];
        DB::execute($sql, $dataDelete);
    }

    // Kiem tra trong db co user nao co email va password ma user nhap khong?
    static public function signIn($data)
    {
        $sql = "SELECT * FROM users WHERE email=:email AND password=:password"; // Lay tat ca info cua user co email va password...
        $user = DB::execute($sql, $data);
        return empty($user) ? "" : $user[0]; // Neu khong tim thay $user thi tra ve rong. Nguoc lai tra ve user.
    }

    // Kiem tra trong db co user nao co email va password ma user nhap khong?
    static public function pagination($page)
    {
        $offset = ($page - 1) * self::USER_PER_PAGE; // Vị trí bắt đầu của trang hiện tại
        $sql = "SELECT * FROM users LIMIT $offset, ". self::USER_PER_PAGE;
        $users = DB::execute($sql);
        return $users; 
    }

}
