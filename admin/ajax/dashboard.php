<?php 

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if(isset($_POST['booking_analytics']))
{
  $frm_data = filteration($_POST);

  $condition = "";

  // Lọc theo khoảng thời gian
  if($frm_data['period'] == 1){
    $condition = "WHERE bo.created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
  }
  else if($frm_data['period'] == 2){
    $condition = "WHERE bo.created_at BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
  }
  else if($frm_data['period'] == 3){
    $condition = "WHERE bo.created_at BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
  }

  // Thống kê đơn đặt phòng
  $result = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT 
      COUNT(bo.booking_id) AS total_bookings,
      COALESCE(SUM(bd.total_pay), 0) AS total_amt,

      COUNT(CASE WHEN bo.booking_status = 'booked' AND bo.arrival = 1 THEN 1 END) AS active_bookings,
      COALESCE(SUM(CASE WHEN bo.booking_status = 'booked' AND bo.arrival = 1 THEN bd.total_pay END), 0) AS active_amt,

      COUNT(CASE WHEN bo.booking_status = 'cancelled' THEN 1 END) AS cancelled_bookings,
      COALESCE(SUM(CASE WHEN bo.booking_status = 'cancelled' THEN bd.total_pay END), 0) AS cancelled_amt

    FROM booking_order bo
    INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
    $condition
  "));

  // Thống kê dịch vụ
  $service_result = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT 
      COALESCE(SUM(bs.quantity), 0) AS total_services_booked,
      COALESCE(SUM(bs.price * bs.quantity), 0) AS total_service_revenue
    FROM booking_services bs
    INNER JOIN booking_order bo ON bs.booking_id = bo.booking_id
    $condition
  "));

  // Gộp kết quả và xuất ra JSON
  echo json_encode(array_merge($result, $service_result));
}


if(isset($_POST['user_analytics']))
{
  $frm_data = filteration($_POST);
  $condition = "";

  if($frm_data['period'] == 1){
    $condition = "WHERE created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
  }
  else if($frm_data['period'] == 2){
    $condition = "WHERE created_at BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
  }
  else if($frm_data['period'] == 3){
    $condition = "WHERE created_at BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
  }

  $total_reviews = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS count FROM rating_review $condition"));
  $total_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS count FROM user_queries $condition"));
  $total_new_reg = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(id) AS count FROM user_cred $condition"));

  echo json_encode([
    'total_queries' => $total_queries['count'],
    'total_reviews' => $total_reviews['count'],
    'total_new_reg' => $total_new_reg['count']
  ]);
}

?>
