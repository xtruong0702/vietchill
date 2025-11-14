<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - Giới thiệu VietChill</title>

  <style>
    .box {
      border-top-color: var(--teal) !important;
      transition: all 0.3s ease;
    }
    .box:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.1);
    }
    .about-img {
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    .h-line {
      width: 80px;
      height: 3px;
      margin: 10px auto;
      background-color: var(--teal);
      border-radius: 2px;
    }
  </style>
</head>

<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- PHẦN GIỚI THIỆU -->
  <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">GIỚI THIỆU VỀ VIETCHILL HOMESTAY</h2>
    <div class="h-line"></div>
    <p class="text-center mt-3 text-muted">
      VietChill – Dịch vụ cho thuê homestay hàng đầu mang đến không gian nghỉ dưỡng <br>
      thoải mái, thân thiện và đậm chất “chill” giữa lòng thiên nhiên Việt Nam.
    </p>
  </div>

  <!-- NỘI DUNG CHÍNH -->
  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-6 mb-4 order-lg-1 order-2">
        <h3 class="mb-3 text-teal">Trải nghiệm “chill” đích thực cùng VietChill</h3>
        <p>
          VietChill Homestay tự hào là hệ thống lưu trú mang phong cách hiện đại kết hợp thiên nhiên. 
          Chúng tôi không chỉ mang đến chỗ ở thoải mái, mà còn là những trải nghiệm đáng nhớ cùng người thân và bạn bè.
          <br><br>
          Với đội ngũ tận tâm và dịch vụ chuyên nghiệp, VietChill luôn đặt sự hài lòng của khách hàng lên hàng đầu – 
          từ khâu đặt phòng, check-in cho đến từng chi tiết nhỏ trong không gian sống.
        </p>
      </div>
      <div class="col-lg-5 col-md-6 mb-4 order-lg-2 order-1">
        <img src="images/about/about.jpg" class="w-100 about-img" alt="VietChill Homestay">
      </div>
    </div>
  </div>

  <!-- THỐNG KÊ -->
<section class="stats-section py-5">
  <div class="container">
    <h2 class="text-center fw-bold mb-5 h-font text-dark">Thống kê VietChill</h2>
    <div class="row text-center">
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card p-4 bg-white rounded-4 shadow">
          <div class="icon-circle mx-auto mb-3">
            <img src="images/about/hotel.svg" width="50" alt="Homestay">
          </div>
          <h4 class="fw-bold text-teal">50+ HOMESTAY</h4>
          <p class="text-muted small mt-2">Trải khắp các điểm du lịch nổi tiếng Việt Nam</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card p-4 bg-white rounded-4 shadow">
          <div class="icon-circle mx-auto mb-3">
            <img src="images/about/customers.svg" width="50" alt="Khách hàng">
          </div>
          <h4 class="fw-bold text-teal">2.000+ KHÁCH HÀNG</h4>
          <p class="text-muted small mt-2">Tin tưởng và lựa chọn VietChill mỗi năm</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card p-4 bg-white rounded-4 shadow">
          <div class="icon-circle mx-auto mb-3">
            <img src="images/about/rating.svg" width="50" alt="Đánh giá">
          </div>
          <h4 class="fw-bold text-teal">4.9/5 ĐÁNH GIÁ</h4>
          <p class="text-muted small mt-2">Sự hài lòng là ưu tiên hàng đầu của chúng tôi</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card p-4 bg-white rounded-4 shadow">
          <div class="icon-circle mx-auto mb-3">
            <img src="images/about/staff.svg" width="50" alt="Nhân sự">
          </div>
          <h4 class="fw-bold text-teal">100+ NHÂN SỰ</h4>
          <p class="text-muted small mt-2">Phục vụ tận tâm – chuyên nghiệp – chu đáo</p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .stats-section {
    background: linear-gradient(180deg, #f9fbff 0%, #ffffff 100%);
  }

  .text-teal {
    color: #009688;
  }

  .stat-card {
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
  }

  .icon-circle {
    width: 90px;
    height: 90px;
    background: rgba(0, 150, 136, 0.1);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease;
  }

  .stat-card:hover .icon-circle {
    background: rgba(0, 150, 136, 0.2);
    transform: scale(1.1);
  }

  @media (max-width: 767px) {
    .icon-circle {
      width: 75px;
      height: 75px;
    }
  }
</style>


<!-- ĐỘI NGŨ VIETCHILL -->
<h3 class="my-5 fw-bold h-font text-center">ĐỘI NGŨ VIETCHILL</h3>

<div class="container px-4">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper mb-5">
      <?php 
        $about_r = selectAll('team_details');
        $path = ABOUT_IMG_PATH;
        while($row = mysqli_fetch_assoc($about_r)){
          echo<<<data
            <div class="swiper-slide bg-white text-center overflow-hidden rounded shadow-sm p-3">
              <div class="team-card">
                <img src="$path$row[picture]" class="w-100 rounded-top" alt="$row[name]" style="object-fit: cover; height: 280px;">
                <div class="p-3">
                  <h5 class="mt-2 text-dark fw-semibold">$row[name]</h5>
                </div>
              </div>
            </div>
          data;
        }
      ?>
    </div>
    <div class="swiper-pagination mt-3"></div>
  </div>
</div>

<!-- chèn footer ở đây -->
<?php require('inc/footer.php'); ?>

<!-- Swiper JS (chỉ 1 lần) -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
  var swiper = new Swiper(".mySwiper", {
    spaceBetween: 30,
    pagination: { el: ".swiper-pagination" },
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    },
    breakpoints: {
      320: { slidesPerView: 1 },
      640: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      1024: { slidesPerView: 3 },
    }
  });
</script>

<style>
  .team-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .team-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
  }
</style>



<!-- Swiper -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
  var swiper = new Swiper(".mySwiper", {
    spaceBetween: 40,
    pagination: { el: ".swiper-pagination" },
    autoplay: {
      delay: 3500,
      disableOnInteraction: false,
    },
    breakpoints: {
      320: { slidesPerView: 1 },
      640: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      1024: { slidesPerView: 4 },
    }
  });
</script>

<style>
  .team-section {
    background: linear-gradient(to bottom, #f9fbff 0%, #ffffff 100%);
  }

  .underline {
    display: block;
    width: 90px;
    height: 3px;
    background-color: #009688;
    margin: 10px auto 0;
    border-radius: 2px;
  }

  .team-card {
    transition: all 0.3s ease;
  }

  .team-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  }

  .team-img {
    position: relative;
    overflow: hidden;
    height: 260px;
  }

  .team-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
  }

  .team-card:hover img {
    transform: scale(1.1);
  }

  .overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 150, 136, 0.55);
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
  }

  .team-card:hover .overlay {
    opacity: 1;
  }

  .social-icons a {
    color: #fff;
    font-size: 1.25rem;
    transition: all 0.2s ease;
  }

  .social-icons a:hover {
    color: #d4f9f3;
  }
</style>


</body>
</html>
