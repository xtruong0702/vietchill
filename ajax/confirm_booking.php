<?php 
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if(isset($_POST['check_availability']))
{
    $frm_data = filteration($_POST);
    $status = "";
    $result = "";

    $today_date = new DateTime(date("Y-m-d"));
    $checkin_date = new DateTime($frm_data['check_in']);
    
    // Nếu không có checkout, tự động set sau checkin 1 ngày
    if(empty($frm_data['check_out'])){
        $checkout_date = clone $checkin_date;
        $checkout_date->modify('+1 day');
    } else {
        $checkout_date = new DateTime($frm_data['check_out']);
    }

    // Validation
    if($checkin_date == $checkout_date){
        $status = 'check_in_out_equal';
        $result = json_encode(["status"=>$status]);
    }
    else if($checkout_date < $checkin_date){
        $status = 'check_out_earlier';
        $result = json_encode(["status"=>$status]);
    }
    else if($checkin_date < $today_date){
        $status = 'check_in_earlier';
        $result = json_encode(["status"=>$status]);
    }

    if($status != ''){
        echo $result;
        exit;
    }

    session_start();

    // Kiểm tra số lượng phòng còn trống
    $tb_query = "SELECT COUNT(*) AS `total_bookings` 
                 FROM `booking_order`
                 WHERE booking_status=? AND room_id=?
                 AND check_out > ? AND check_in < ?";

    $values = ['booked', $_SESSION['room']['id'], $checkin_date->format('Y-m-d'), $checkout_date->format('Y-m-d')];
    $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));
    
    $rq_result = select("SELECT `quantity`, `price` FROM `rooms` WHERE `id`=?", [$_SESSION['room']['id']], 'i');
    $rq_fetch = mysqli_fetch_assoc($rq_result);

    if(($rq_fetch['quantity'] - $tb_fetch['total_bookings']) == 0){
        $status = 'unavailable';
        $result = json_encode(['status'=>$status]);
        echo $result;
        exit;
    }

    // Tính số ngày và tiền
    $count_days = date_diff($checkin_date, $checkout_date)->days;
    $payment = $rq_fetch['price'] * $count_days;

    $_SESSION['room']['payment'] = $payment;
    $_SESSION['room']['available'] = true;

    $result = json_encode([
        "status"=>'available', 
        "days"=>$count_days, 
        "payment"=> $payment,
        "checkout"=>$checkout_date->format('Y-m-d') // gửi lại ngày checkout tự động
    ]);
    echo $result;
}
?>
