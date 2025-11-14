<?php
// ==== DEFINE CONSTANTS CHỈ KHI CHƯA TỒN TẠI ====
if(!defined('SITE_URL')) define('SITE_URL', 'http://localhost/vietchill/');
if(!defined('ABOUT_IMG_PATH')) define('ABOUT_IMG_PATH', SITE_URL.'images/about/');
if(!defined('CAROUSEL_IMG_PATH')) define('CAROUSEL_IMG_PATH', SITE_URL.'images/carousel/');
if(!defined('FACILITIES_IMG_PATH')) define('FACILITIES_IMG_PATH', SITE_URL.'images/facilities/');
if(!defined('ROOMS_IMG_PATH')) define('ROOMS_IMG_PATH', SITE_URL.'images/rooms/');
if(!defined('USERS_IMG_PATH')) define('USERS_IMG_PATH', SITE_URL.'images/users/');
if(!defined('SERVICES_IMG_PATH')) define('SERVICES_IMG_PATH', SITE_URL.'images/services/');

if(!defined('UPLOAD_IMAGE_PATH')) define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/vietchill/images/');
if(!defined('ABOUT_FOLDER')) define('ABOUT_FOLDER','about/');
if(!defined('CAROUSEL_FOLDER')) define('CAROUSEL_FOLDER','carousel/');
if(!defined('FACILITIES_FOLDER')) define('FACILITIES_FOLDER','facilities/');
if(!defined('ROOMS_FOLDER')) define('ROOMS_FOLDER','rooms/');
if(!defined('USERS_FOLDER')) define('USERS_FOLDER','users/');
if(!defined('SERVICES_FOLDER')) define('SERVICES_FOLDER','services/');

// ==== HÀM ====

// Hàm kiểm tra admin login
if(!function_exists('adminLogin')){
    function adminLogin() {
        session_start();
        if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] === true)){
            echo "<script>window.location.href='index.php'</script>";
            exit;
        }
    }
}

// Hàm redirect
if(!function_exists('redirect')){
    function redirect($url) {
        echo "<script>window.location.href='$url'</script>";
        exit;
    }
}

// Hàm alert bootstrap
if(!function_exists('alert')){
    function alert($type, $msg) {
        $bs_class = ($type === 'success') ? 'alert-success' : 'alert-danger';
        echo <<<alert
            <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
                <strong class="me-3">$msg</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        alert;
    }
}

// Hàm upload hình ảnh chung
if(!function_exists('uploadImage')){
    function uploadImage($image, $folder) {
        $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
        $img_mime = $image['type'];

        if(!in_array($img_mime,$valid_mime)) return 'Không hỗ trợ định dạng này!';
        if(($image['size']/(1024*1024)) > 2) return 'Vui lòng chọn hình ảnh dưới 2MB!';

        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_'.random_int(11111,99999).".$ext";
        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;

        if(!is_dir(UPLOAD_IMAGE_PATH.$folder)) mkdir(UPLOAD_IMAGE_PATH.$folder, 0777, true);

        return move_uploaded_file($image['tmp_name'],$img_path) ? $rname : 'Tải lên hình ảnh thất bại!';
    }
}

// Hàm xóa hình ảnh
if(!function_exists('deleteImage')){
    function deleteImage($image, $folder) {
        if($image === 'default.jpg') return true;

        $img_path = UPLOAD_IMAGE_PATH.$folder.$image;
        return file_exists($img_path) && unlink($img_path);
    }
}

// Hàm upload SVG
if(!function_exists('uploadSVGImage')){
    function uploadSVGImage($image,$folder) {
        $valid_mime = ['image/svg+xml'];
        $img_mime = $image['type'];

        if(!in_array($img_mime,$valid_mime)) return 'Không hỗ trợ định dạng này!';
        if(($image['size']/(1024*1024))>1) return 'Vui lòng chọn hình ảnh dưới 1MB!';

        $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
        $rname = 'IMG_'.random_int(11111,99999).".$ext";
        $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;

        if(!is_dir(UPLOAD_IMAGE_PATH.$folder)) mkdir(UPLOAD_IMAGE_PATH.$folder, 0777, true);

        return move_uploaded_file($image['tmp_name'],$img_path) ? $rname : 'Tải lên hình ảnh thất bại!';
    }
}

// Hàm upload hình ảnh user
if(!function_exists('uploadUserImage')){
    function uploadUserImage($image) {
        $valid_mime = ['image/jpeg','image/png','image/webp'];
        $img_mime = $image['type'];

        if(!in_array($img_mime,$valid_mime)) return 'inv_img';
        if(($image['size']/(1024*1024)) > 2) return 'inv_size';

        $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
        $rname = 'IMG_'.random_int(11111,99999).".jpeg";
        $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

        if(!is_dir(UPLOAD_IMAGE_PATH.USERS_FOLDER)) mkdir(UPLOAD_IMAGE_PATH.USERS_FOLDER, 0777, true);

        $img = false;
        if($ext === 'png' || $ext === 'PNG') $img = imagecreatefrompng($image['tmp_name']);
        else if($ext === 'webp' || $ext === 'WEBP') $img = imagecreatefromwebp($image['tmp_name']);
        else if($ext === 'jpg' || $ext === 'jpeg' || $ext === 'JPG' || $ext === 'JPEG') $img = imagecreatefromjpeg($image['tmp_name']);

        if($img === false) return 'upd_failed';
        $res = imagejpeg($img, $img_path, 75);
        imagedestroy($img);
        return $res ? $rname : 'upd_failed';
    }
}

// Tạo hình mặc định cho user nếu chưa có
if(!function_exists('createDefaultUserImage')){
    function createDefaultUserImage() {
        $default_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.'default.jpg';
        if(!file_exists($default_path)){
            if(!is_dir(UPLOAD_IMAGE_PATH.USERS_FOLDER)) mkdir(UPLOAD_IMAGE_PATH.USERS_FOLDER, 0777, true);

            $img = imagecreate(100, 100);
            $bg = imagecolorallocate($img, 200, 200, 200);
            $text_color = imagecolorallocate($img, 100, 100, 100);
            imagestring($img, 3, 25, 40, 'USER', $text_color);
            imagejpeg($img, $default_path, 75);
            imagedestroy($img);
        }
    }
}

// Đảm bảo hình mặc định tồn tại
createDefaultUserImage();
?>
