<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link  rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Trang chủ</title>
  <style>
  /* Form đặt phòng */
  .availability-form {
    margin-top: -60px;
    z-index: 2;
    position: relative;
  }

  @media screen and (max-width: 575px) {
    .availability-form {
      margin-top: 25px;
      padding: 0 35px;
    } 
  }

  /* --- Carousel (Swiper) --- */
  .swiper-container {
    height: 400px; /* ↓ giảm chiều cao tổng thể */
    border-radius: 15px;
    overflow: hidden;
    position: relative;
  }

  .swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Giữ tỷ lệ ảnh */
    filter: brightness(90%);
    transition: transform 0.5s ease;
  }

  .swiper-slide img:hover {
    transform: scale(1.03); /* Hiệu ứng phóng nhẹ khi rê chuột */
  }

  @media screen and (max-width: 768px) {
    .swiper-container {
      height: 280px; /* giảm thêm cho màn nhỏ */
    }
  }
</style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- Carousel -->
  <div class="container-fluid px-lg-4 mt-4">
    <div class="swiper swiper-container">
      <div class="swiper-wrapper">
        <?php 
          $res = selectAll('carousel');
          while($row = mysqli_fetch_assoc($res))
          {
            $path = CAROUSEL_IMG_PATH;
            echo <<<data
              <div class="swiper-slide">
                <img src="$path$row[image]" class="w-100 d-block" alt="Carousel Image">
              </div>
            data;
          }
        ?>
      </div>
    </div>
  </div>


  <!-- check availability form -->

  <!-- Form đặt phòng -->
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-10 bg-white border rounded-4 shadow-sm p-4">
      <h4 class="fw-bold text-center text-primary mb-4 h-font">
        <i class="bi bi-calendar-check me-2"></i> Tiến hành đặt phòng
      </h4>

      <form action="rooms.php" method="get" class="needs-validation">
        <div class="row g-4 align-items-end">
          <!-- Ngày nhận phòng -->
          <div class="col-md-3">
            <label class="form-label fw-semibold">Nhận phòng</label>
            <input type="date" class="form-control shadow-none border-primary-subtle" 
                   name="checkin" id="checkin" required>
          </div>

          <!-- Ngày trả phòng -->
          <div class="col-md-3">
            <label class="form-label fw-semibold">Trả phòng</label>
            <input type="date" class="form-control shadow-none border-primary-subtle" 
                   name="checkout" id="checkout" required>
          </div>

          <!-- Người lớn -->
          <div class="col-md-2">
            <label class="form-label fw-semibold">Người lớn</label>
            <select class="form-select shadow-none border-primary-subtle" name="adult">
              <?php 
                $guests_q = mysqli_query($con,"SELECT MAX(adult) AS `max_adult`, MAX(children) AS `max_children` 
                  FROM `rooms` WHERE `status`='1' AND `removed`='0'");  
                $guests_res = mysqli_fetch_assoc($guests_q);
                for($i=1; $i<=$guests_res['max_adult']; $i++){
                  echo "<option value='$i'>$i</option>";
                }
              ?>
            </select>
          </div>

          <!-- Trẻ em -->
          <div class="col-md-2">
            <label class="form-label fw-semibold">Trẻ em</label>
            <select class="form-select shadow-none border-primary-subtle" name="children">
              <?php 
                for($i=0; $i<=$guests_res['max_children']; $i++){
                  echo "<option value='$i'>$i</option>";
                }
              ?>
            </select>
          </div>

          <!-- Nút tìm kiếm -->
          <div class="col-md-2 text-center">
            <input type="hidden" name="check_availability">
            <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm py-2 fw-semibold">
              <i class="bi bi-search me-1"></i> Tìm kiếm
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- CSS -->
<style>
  .h-font {
    font-family: 'Poppins', sans-serif;
  }
  .form-label {
    color: #333;
  }
  .form-control, .form-select {
    border-radius: 10px;
    transition: all 0.3s ease;
  }
  .form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 6px rgba(13, 110, 253, 0.25);
  }
  .btn-primary {
    background: linear-gradient(90deg, #007bff, #0056d2);
    border: none;
  }
  .btn-primary:hover {
    background: linear-gradient(90deg, #0056d2, #0041a8);
    transform: translateY(-2px);
  }
</style>

<!-- JavaScript xử lý ngày -->
<script>
  const checkinInput = document.getElementById('checkin');
  const checkoutInput = document.getElementById('checkout');

  checkinInput.addEventListener('change', function() {
    const checkinDate = new Date(this.value);
    if (!isNaN(checkinDate.getTime())) {
      // Cộng thêm 1 ngày
      const nextDay = new Date(checkinDate);
      nextDay.setDate(checkinDate.getDate() + 1);

      // Format yyyy-mm-dd
      const formattedNextDay = nextDay.toISOString().split('T')[0];

      // Gán giá trị cho checkout
      checkoutInput.min = this.value;
      checkoutInput.value = formattedNextDay;
    }
  });
</script>

<!-- Our Rooms -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center fw-bold text-uppercase text-primary mb-5 h-font">Danh sách phòng</h2>

    <div class="row g-4 justify-content-center">
      <?php 
      $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC", [1,0], 'ii');

      while($room_data = mysqli_fetch_assoc($room_res))
      {
        // Features
        $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f 
          INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
          WHERE rfea.room_id = '$room_data[id]'");
        $features_data = "";
        while($fea_row = mysqli_fetch_assoc($fea_q)){
          $features_data .="<span class='badge bg-white text-dark border me-1 mb-1'>$fea_row[name]</span>";
        }

        // Facilities
        $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f 
          INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
          WHERE rfac.room_id = '$room_data[id]'");
        $facilities_data = "";
        while($fac_row = mysqli_fetch_assoc($fac_q)){
          $facilities_data .="<span class='badge bg-white text-dark border me-1 mb-1'>$fac_row[name]</span>";
        }

        // Thumbnail
        $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
        $thumb_q = mysqli_query($con,"SELECT * FROM `room_images` 
          WHERE `room_id`='$room_data[id]' AND `thumb`='1'");
        if(mysqli_num_rows($thumb_q)>0){
          $thumb_res = mysqli_fetch_assoc($thumb_q);
          $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
        }

        // Rating
        $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
          WHERE `room_id`='$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20";
        $rating_res = mysqli_query($con,$rating_q);
        $rating_fetch = mysqli_fetch_assoc($rating_res);
        $stars = 0;
        if($rating_fetch['avg_rating']!=NULL){
          $stars = round($rating_fetch['avg_rating']);
        }

        $rating_html = "<div class='mt-2'>";
        for($i=0; $i<5; $i++){
          $rating_html .= $i < $stars ? "<i class='bi bi-star-fill text-warning'></i>" : "<i class='bi bi-star text-muted'></i>";
        }
        $rating_html .= "</div>";

        // Booking button
        $book_btn = "";
        if(!$settings_r['shutdown']){
          $login=0;
          if(isset($_SESSION['login']) && $_SESSION['login']==true){
            $login=1;
          }
          $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' 
                      class='btn btn-primary rounded-pill px-4 shadow-none'>
                      <i class='bi bi-calendar-check'></i> Đặt ngay
                    </button>";
        }

        echo <<<data
        <div class="col-lg-4 col-md-6 col-sm-10">
          <div class="room-card border border-2 border-light-subtle bg-white rounded-4 overflow-hidden shadow-sm h-100 d-flex flex-column">
            <div class="room-image position-relative overflow-hidden">
              <img src="$room_thumb" class="w-100" style="height: 240px; object-fit: cover;">
              <span class="position-absolute top-0 end-0 bg-primary text-white fw-semibold px-3 py-1 rounded-start">
                $room_data[price] VND / đêm
              </span>
            </div>
            <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
              <div>
                <h5 class="fw-bold mb-2 text-dark">$room_data[name]</h5>
                $rating_html
                <div class="mt-3">
                  <h6 class="text-muted mb-1"><i class="bi bi-grid"></i> Không gian:</h6>
                  <div>$features_data</div>
                </div>
                <div class="mt-3">
                  <h6 class="text-muted mb-1"><i class="bi bi-tools"></i> Tiện nghi:</h6>
                  <div>$facilities_data</div>
                </div>
                <div class="mt-3">
                  <h6 class="text-muted mb-1"><i class="bi bi-people"></i> Sức chứa:</h6>
                  <span class="badge bg-light text-dark border">$room_data[adult] Người lớn</span>
                  <span class="badge bg-light text-dark border">$room_data[children] Trẻ em</span>
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center mt-4">
                $book_btn
                <a href="room_details.php?id=$room_data[id]" class="btn btn-outline-dark rounded-pill px-4">
                  <i class="bi bi-eye"></i> Chi tiết
                </a>
              </div>
            </div>
          </div>
        </div>
        data;
      }
      ?>

      <div class="col-12 text-center mt-5">
        <a href="rooms.php" class="btn btn-outline-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
          Xem thêm phòng >>
        </a>
      </div>
    </div>
  </div>
</section>

<style>
  .room-card {
    border-radius: 16px;
    border: 2px solid #dee2e6; /* viền nhẹ */
    transition: all 0.3s ease;
  }
  .room-card:hover {
    transform: translateY(-6px);
    border-color: #0d6efd; /* đổi màu viền khi hover */
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }
  .room-image img {
    transition: transform 0.4s ease;
  }
  .room-card:hover .room-image img {
    transform: scale(1.05);
  }
  .room-card .p-4 {
    border-top: 1px solid #eee;
  }
  h2.h-font {
    font-family: 'Poppins', sans-serif;
    letter-spacing: 1px;
  }
</style>




  <!-- Our Facilities -->
<section class="facilities-section py-5 bg-light">
  <div class="container">
    <h2 class="text-center fw-bold mb-5 h-font position-relative">
      <span class="section-title">Các tiện ích</span>
    </h2>

    <div class="row justify-content-center">
      <?php 
        $res = mysqli_query($con,"SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5");
        $path = FACILITIES_IMG_PATH;

        while($row = mysqli_fetch_assoc($res)){
          echo<<<data
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
              <div class="facility-card text-center bg-white rounded-4 shadow-sm p-4 h-100">
                <div class="icon-wrapper mx-auto mb-3">
                  <img src="$path$row[icon]" class="facility-icon" alt="$row[name]">
                </div>
                <h5 class="mt-2 text-dark fw-semibold">$row[name]</h5>
              </div>
            </div>
          data;
        }
      ?>

      <div class="col-lg-12 text-center mt-4">
        <a href="facilities.php" class="btn btn-dark px-4 py-2 rounded-pill fw-bold shadow">
          Tìm hiểu thêm &nbsp; →
        </a>
      </div>
    </div>
  </div>
</section>

<style>
  .facilities-section {
    background: linear-gradient(to bottom, #f9f9f9, #ffffff);
  }

  .section-title::after {
    content: '';
    display: block;
    width: 80px;
    height: 3px;
    background: #0d6efd;
    margin: 10px auto 0;
    border-radius: 2px;
  }

  .facility-card {
    transition: all 0.3s ease;
  }

  .facility-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .icon-wrapper {
    background: #f1f5ff;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .facility-icon {
    width: 50px;
    height: 50px;
    object-fit: contain;
  }

  @media (max-width: 767px) {
    .facility-card {
      padding: 1.5rem 1rem;
    }
  }
</style>

  <!-- Testimonials -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Đánh giá dịch vụ</h2>

  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <?php

          $review_q = "SELECT rr.*,uc.name AS uname, uc.profile, r.name AS rname FROM `rating_review` rr
            INNER JOIN `user_cred` uc ON rr.user_id = uc.id
            INNER JOIN `rooms` r ON rr.room_id = r.id
            ORDER BY `sr_no` DESC LIMIT 6";

          $review_res = mysqli_query($con,$review_q);
          $img_path = USERS_IMG_PATH;

          if(mysqli_num_rows($review_res)==0){
            echo 'No reviews yet!';
          }
          else
          {
            while($row = mysqli_fetch_assoc($review_res))
            {
              $stars = "<i class='bi bi-star-fill text-warning'></i> ";
              for($i=1; $i<$row['rating']; $i++){
                $stars .= " <i class='bi bi-star-fill text-warning'></i>";
              }

              echo<<<slides
                <div class="swiper-slide bg-white p-4">
                  <div class="profile d-flex align-items-center mb-3">
                    <img src="$img_path$row[profile]" class="rounded-circle" loading="lazy" width="30px">
                    <h6 class="m-0 ms-2">$row[uname]</h6>
                  </div>
                  <p>
                    $row[review]
                  </p>
                  <div class="rating">
                    $stars
                  </div>
                </div>
              slides;
            }
          }
        
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>

  <!-- Reach us -->

  <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Liên hệ</h2>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <!-- <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy"></iframe> -->
         <iframe class="w-100 rounded" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29793.9884588654!2d105.81636405839672!3d21.02273835997583!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab9bd9861ca1%3A0xe7887f7b72ca17a9!2zSMOgIE7hu5lpLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1750776114658!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
          <h5>Tổng đài viên</h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
          </a>
        </div>
        <div class="bg-white p-4 rounded mb-2">
          <h5>Theo dõi chúng tôi</h5>
          <?php 
            if($contact_r['tw']!=''){
              echo<<<data
                <a href="$contact_r[tw]" class="d-inline-block mb-3">
                  <span class="badge bg-light text-dark fs-6 p-2"> 
                  <i class="bi bi-twitter me-1"></i> Twitter
                  </span>
                </a>
                <br>
              data;
            }
          ?>

          <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
            <span class="badge bg-light text-dark fs-6 p-2"> 
            <i class="bi bi-facebook me-1"></i> Facebook
            </span>
          </a>
          <br>
          <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block">
            <span class="badge bg-light text-dark fs-6 p-2"> 
            <i class="bi bi-instagram me-1"></i> Instagram
            </span>
          </a>
        </div>
        <div class="p-4 rounded">
          <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Tìm hiểu thêm >>></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Password reset modal and code -->

  <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="recovery-form">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
              <i class="bi bi-shield-lock fs-3 me-2"></i> Tạo mật khẩu mới
            </h5>
          </div>
          <div class="modal-body">
            <div class="mb-4">
              <label class="form-label">Mật khẩu mới</label>
              <input type="password" name="pass" required class="form-control shadow-none">
              <input type="hidden" name="email">
              <input type="hidden" name="token">
            </div>
            <div class="mb-2 text-end">
              <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">Huỷ</button>
              <button type="submit" class="btn btn-dark shadow-none">Tiếp tục</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

  <?php
  
    if(isset($_GET['account_recovery']))
    {
      $data = filteration($_GET);

      $t_date = date("Y-m-d");

      $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
        [$data['email'],$data['token'],$t_date],'sss');

      if(mysqli_num_rows($query)==1)
      {
        echo<<<showModal
          <script>
            var myModal = document.getElementById('recoveryModal');

            myModal.querySelector("input[name='email']").value = '$data[email]';
            myModal.querySelector("input[name='token']").value = '$data[token]';

            var modal = bootstrap.Modal.getOrCreateInstance(myModal);
            modal.show();
          </script>
        showModal;
      }
      else{
        alert("error","Liên kết không còn khả dụng!");
      }

    }

  ?>
  
  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
    });

    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      loop: true,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });

    // recover account
    
    let recovery_form = document.getElementById('recovery-form');

    recovery_form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let data = new FormData();

      data.append('email',recovery_form.elements['email'].value);
      data.append('token',recovery_form.elements['token'].value);
      data.append('pass',recovery_form.elements['pass'].value);
      data.append('recover_user','');

      var myModal = document.getElementById('recoveryModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/login_register.php",true);

      xhr.onload = function(){
        if(this.responseText == 'failed'){
          alert('error',"Khôi phục tài khoản thất bại!");
        }
        else{
          alert('success',"Khôi phục tài khoản thành công!");
          recovery_form.reset();
        }
      }

      xhr.send(data);
    });

  </script>

</body>
</html>