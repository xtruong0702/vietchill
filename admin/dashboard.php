<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Quản Lý</title>
  <?php require('inc/links.php'); ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f5f6f8;
    }

    .card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 14px; /* Bo góc mềm */
      padding: 18px;
      transition: all 0.25s ease;
    }

    .card-hover:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08); /* Shadow nhẹ */
    }

    .card h6 {
      font-weight: 600;
      font-size: 0.8rem;
      color: #6b7280;
      margin-top: 8px;
      text-transform: uppercase;
    }

    .card h1, .card h5 {
      color: #111827;
      font-weight: 700;
    }

    .icon-box {
      width: 45px;
      height: 45px;
      background: #e8efff;
      color: #0d6efd;
      border-radius: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0 auto 10px auto;
      font-size: 1.5rem;
    }

    .section-title {
      font-weight: 650;
      border-left: 3px solid #0d6efd;
      padding-left: 10px;
      margin-bottom: 1rem;
      color: #1f2937;
    }

    .badge {
      border-radius: 10px !important;
    }

    a.text-decoration-none:hover {
      text-decoration: none;
    }
  </style>
</head>
<body class="bg-light">

<?php 
require('inc/header.php'); 

// Lấy dữ liệu
$is_shutdown = mysqli_fetch_assoc(mysqli_query($con,"SELECT `shutdown` FROM `settings`"));
$current_bookings = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
  COUNT(CASE WHEN booking_status IN ('booked','pending') THEN 1 END) AS `new_bookings`,
  COUNT(CASE WHEN booking_status='cancelled' AND refund=0 THEN 1 END) AS `refund_bookings`
  FROM `booking_order`"));
$unread_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count` FROM `user_queries` WHERE `seen`=0"));
$unread_reviews = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count` FROM `rating_review` WHERE `seen`=0"));
$current_users = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
  COUNT(id) AS `total`,
  COUNT(CASE WHEN `status`=1 THEN 1 END) AS `active`,
  COUNT(CASE WHEN `status`=0 THEN 1 END) AS `inactive`,
  COUNT(CASE WHEN `is_verified`=0 THEN 1 END) AS `unverified`
  FROM `user_cred`"));  
?>

<div class="container-fluid py-4" id="main-content">
  <div class="row">
    <div class="col-lg-10 ms-auto p-4">

      <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="text-primary fw-bold">Dashboard</h3>
        <?php if($is_shutdown['shutdown']): ?>
          <span class="badge bg-danger py-2 px-3 rounded">Shutdown Mode Active!</span>
        <?php endif; ?>
      </div>

      <!-- THỐNG KÊ CHÍNH -->
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <a href="new_bookings.php" class="text-decoration-none">
            <div class="card card-hover text-center">
              <div class="icon-box">
                <i class="bi bi-house-fill"></i>
              </div>
              <h6>Phòng đã cho thuê</h6>
              <h1><?php echo $current_bookings['new_bookings'] ?></h1>
            </div>
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="refund_bookings.php" class="text-decoration-none">
            <div class="card card-hover text-center">
              <div class="icon-box">
                <i class="bi bi-cash-stack"></i>
              </div>
              <h6>Lượt hoàn tiền</h6>
              <h1><?php echo $current_bookings['refund_bookings'] ?></h1>
            </div>
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="user_queries.php" class="text-decoration-none">
            <div class="card card-hover text-center">
              <div class="icon-box">
                <i class="bi bi-chat-left-text"></i>
              </div>
              <h6>Tin nhắn chưa đọc</h6>
              <h1><?php echo $unread_queries['count'] ?></h1>
            </div>
          </a>
        </div>
        <div class="col-md-3 mb-3">
          <a href="rate_review.php" class="text-decoration-none">
            <div class="card card-hover text-center">
              <div class="icon-box">
                <i class="bi bi-star-fill"></i>
              </div>
              <h6>Lượt đánh giá</h6>
              <h1><?php echo $unread_reviews['count'] ?></h1>
            </div>
          </a>
        </div>
      </div>

      <!-- BOOKING ANALYTICS -->
      <h5 class="section-title">Thống kê</h5>
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <div class="icon-box">
              <i class="bi bi-calendar-check-fill"></i>
            </div>
            <h6>Tổng lượt đặt phòng</h6>
            <h1 id="total_bookings">0</h1>
            <h5 id="total_amt">0 triệu đồng</h5>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <div class="icon-box">
              <i class="bi bi-check-circle-fill"></i>
            </div>
            <h6>Phòng đã cho thuê</h6>
            <h1 id="active_bookings">0</h1>
            <h5 id="active_amt">0 triệu đồng</h5>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <div class="icon-box">
              <i class="bi bi-x-circle-fill"></i>
            </div>
            <h6>Phòng đã hủy</h6>
            <h1 id="cancelled_bookings">0</h1>
            <h5 id="cancelled_amt">0 triệu đồng</h5>
          </div>
        </div>
      </div>

      <!-- SERVICE ANALYTICS -->
      <h5 class="section-title">Dịch vụ</h5>
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <div class="icon-box">
              <i class="bi bi-bag-check-fill"></i>
            </div>
            <h6>Dịch vụ đã đặt</h6>
            <h1 id="total_services_booked">0</h1>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <div class="icon-box">
              <i class="bi bi-currency-dollar"></i>
            </div>
            <h6>Doanh thu dịch vụ</h6>
            <h1 id="total_service_revenue">0 triệu đồng</h1>
          </div>
        </div>
      </div>

      <!-- USER STATS -->
      <h5 class="section-title">Người dùng</h5>
      <div class="row mb-4">
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <h6>Tổng</h6>
            <h1><?php echo $current_users['total'] ?></h1>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <h6>Đang hoạt động</h6>
            <h1><?php echo $current_users['active'] ?></h1>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <h6>Không hoạt động</h6>
            <h1><?php echo $current_users['inactive'] ?></h1>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card card-hover text-center">
            <h6>Chưa xác minh</h6>
            <h1><?php echo $current_users['unverified'] ?></h1>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php require('inc/scripts.php'); ?>
<script src="scripts/dashboard.js?v=<?php echo time(); ?>"></script>

</body>
</html>
