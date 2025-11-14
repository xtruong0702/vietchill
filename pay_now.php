<?php 
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

  if(isset($_POST['pay_now']))
  {
    // Lấy dữ liệu người dùng và phòng
    $CUST_ID = $_SESSION['uId'];
    $TXN_AMOUNT = $_SESSION['room']['payment'];
    $ORDER_ID = 'ORD_'.$CUST_ID.random_int(11111,9999999);

    $frm_data = filteration($_POST);

    // --- Thêm booking vào bảng booking_order ---
    $query1 = "INSERT INTO `booking_order` 
      (`user_id`, `room_id`, `check_in`, `check_out`, `order_id`, `datetime`, `arrival`, `booking_status`) 
      VALUES (?,?,?,?,?,NOW(),0,'booked')";

    insert($query1, [
      $CUST_ID,
      $_SESSION['room']['id'],
      $frm_data['checkin'],
      $frm_data['checkout'],
      $ORDER_ID
    ], 'issss');

    // Lấy ID booking vừa insert
    $booking_id = mysqli_insert_id($con);

    // --- Thêm chi tiết booking vào booking_details ---
    $query2 = "INSERT INTO `booking_details` 
      (`booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`) 
      VALUES (?,?,?,?,?,?,?)";

    insert($query2, [
      $booking_id,
      $_SESSION['room']['name'],
      $_SESSION['room']['price'],
      $TXN_AMOUNT,
      $frm_data['name'],
      $frm_data['phonenum'],
      $frm_data['address']
    ], 'issssss');

    // Redirect về trang bookings
    redirect('bookings.php');
  }
?>
