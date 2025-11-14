<?php
// Cấu hình kết nối
$host = '127.0.0.1'; // hoặc 'localhost'
$port = 3306;        
$user = 'root';
$pass = '';
$db   = 'vietchill';

// Kết nối MySQL
$con = mysqli_connect($host, $user, $pass, $db, $port);

// Kiểm tra kết nối
if(!$con){
    die("Cannot Connect to Database: " . mysqli_connect_error());
}

// Đặt charset
mysqli_set_charset($con, "utf8mb4");

// Hàm lọc dữ liệu đầu vào
if(!function_exists('filteration')){
    function filteration($data) {
        foreach($data as $key => $value){
            $value = trim($value);
            $value = htmlspecialchars($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $data[$key] = $value;
        }
        return $data;
    }
}

// Hàm SELECT * FROM table
if(!function_exists('selectAll')){
    function selectAll($table) {
        $con = $GLOBALS['con'];
        $res = mysqli_query($con, "SELECT * FROM $table");
        return $res;
    }
}

// Hàm SELECT với prepared statement
if(!function_exists('select')){
    function select($sql, $values, $datatypes) {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            } else {
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Execute");
            }
        } else {
            die("Query cannot be executed - Select");
        }
    }
}

// Hàm UPDATE
if(!function_exists('update')){
    function update($sql, $values, $datatypes) {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            } else {
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Update");
            }
        } else {
            die("Query cannot be executed - Update");
        }
    }
}

// Hàm INSERT
if(!function_exists('insert')){
    function insert($sql, $values, $datatypes) {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            } else {
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Insert");
            }
        } else {
            die("Query cannot be executed - Insert");
        }
    }
}

// Hàm DELETE
if(!function_exists('delete')){
    function delete($sql, $values, $datatypes) {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql)){
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            } else {
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Delete");
            }
        } else {
            die("Query cannot be executed - Delete");
        }
    }
}
?>
