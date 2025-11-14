<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');

// Nếu có thông báo từ form
$msg = '';
if(isset($_POST['send']))
{
    $frm_data = filteration($_POST); // đảm bảo filteration() chỉ khai báo 1 lần

    $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
    $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

    $res = insert($q,$values,'ssss');
    if($res==1){
        $msg = '<div class="alert alert-success text-center">Email đã được gửi đi!</div>';
    }
    else{
        $msg = '<div class="alert alert-danger text-center">Hệ thống đang bảo trì! Hãy thử lại sau ít phút.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Liên hệ</title>
  <style>
    .contact-card{background:#fff; border-radius:10px; padding:20px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
    .custom-btn{background:#007bff;color:#fff;border:none;padding:10px 20px;border-radius:5px;}
    .h-line{width:100px;height:3px;background:#000;margin:auto;}
  </style>
</head>
<body class="bg-light">

<?php require('inc/header.php'); ?>

<div class="my-5 px-4 text-center">
  <h2 class="fw-bold h-font">LIÊN HỆ</h2>
  <div class="h-line my-3"></div>
  <p class="text-muted mx-auto" style="max-width:700px;">
    Chúng tôi luôn sẵn sàng hỗ trợ bạn! Liên hệ qua hotline, email, hoặc biểu mẫu trực tuyến để được tư vấn.
  </p>
</div>

<div class="container">
  <div class="row gx-4 gy-4">

    <!-- Thông tin liên hệ -->
    <div class="col-lg-6 col-md-6">
      <div class="contact-card">
        <h4 class="fw-bold mb-3">Thông tin liên hệ</h4>

        <h5>Địa chỉ</h5>
        <a href="<?php echo $contact_r['gmap'] ?>" target="_blank">
          <i class="bi bi-geo-alt-fill me-2"></i> <?php echo $contact_r['address'] ?>
        </a>

        <h5 class="mt-4">Tổng đài viên</h5>
        <a href="tel:+<?php echo $contact_r['pn1'] ?>">
          <i class="bi bi-telephone-fill me-2"></i> +<?php echo $contact_r['pn1'] ?>
        </a>

        <h5 class="mt-4">Email</h5>
        <a href="mailto:<?php echo $contact_r['email'] ?>">
          <i class="bi bi-envelope-fill me-2"></i> <?php echo $contact_r['email'] ?>
        </a>

        <h5 class="mt-4">Theo dõi chúng tôi</h5>
        <?php if(!empty($contact_r['tw'])): ?>
          <a href="<?php echo $contact_r['tw']; ?>" class="me-2 text-dark fs-5"><i class="bi bi-twitter"></i></a>
        <?php endif; ?>
        <a href="<?php echo $contact_r['fb']; ?>" class="me-2 text-dark fs-5"><i class="bi bi-facebook"></i></a>
        <a href="<?php echo $contact_r['insta']; ?>" class="text-dark fs-5"><i class="bi bi-instagram"></i></a>
      </div>
    </div>

    <!-- Form gửi tin nhắn -->
    <div class="col-lg-6 col-md-6">
      <div class="contact-card">
        <?php echo $msg; ?>
        <form method="POST">
          <h5 class="fw-bold mb-3">Để lại lời nhắn</h5>
          <div class="mb-3">
            <label class="form-label fw-medium">Tên</label>
            <input name="name" type="text" class="form-control shadow-none" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Email</label>
            <input name="email" type="email" class="form-control shadow-none" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Tiêu đề</label>
            <input name="subject" type="text" class="form-control shadow-none" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Nội dung</label>
            <textarea name="message" class="form-control shadow-none" rows="5" style="resize:none;" required></textarea>
          </div>
          <button type="submit" name="send" class="custom-btn">Gửi</button>
        </form>
      </div>
    </div>

    

  </div>
</div>

<?php require('inc/footer.php'); ?>
</body>
</html>
