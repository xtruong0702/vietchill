<?php 
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set content type
header('Content-Type: text/plain');

// Log all POST data for debugging
error_log("POST data received: " . print_r($_POST, true));
if(isset($_FILES['profile'])) {
    error_log("File data received: " . print_r($_FILES['profile'], true));
}

if(isset($_POST['register'])) {
    try {
        error_log("Registration process started");
        
        $data = filteration($_POST);

        // Validate required fields
        if(empty($data['name']) || empty($data['email']) || empty($data['phonenum']) || 
           empty($data['address']) || empty($data['pincode']) || empty($data['dob']) || 
           empty($data['pass']) || empty($data['cpass'])) {
            error_log("Missing required fields");
            echo 'missing_fields';
            exit;
        }

        // Match password and confirm password field
        if($data['pass'] != $data['cpass']) {
            error_log("Password mismatch");
            echo 'pass_mismatch';
            exit;
        }

        // Password strength check
        if(strlen($data['pass']) < 6) {
            error_log("Password too short");
            echo 'pass_too_short';
            exit;
        }

        // Email validation
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            error_log("Invalid email format");
            echo 'invalid_email';
            exit;
        }

        // Phone number validation (basic)
        if(!preg_match('/^[0-9]{10,15}$/', $data['phonenum'])) {
            error_log("Invalid phone number format");
            echo 'invalid_phone';
            exit;
        }

        // Check if user already exists
        $u_exist = select("SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1", 
                         [$data['email'], $data['phonenum']], "ss");
        
        if(mysqli_num_rows($u_exist) != 0) {
            $u_exist_fetch = mysqli_fetch_assoc($u_exist);
            if($u_exist_fetch['email'] == $data['email']) {
                error_log("Email already exists: " . $data['email']);
                echo 'email_already';
            } else {
                error_log("Phone already exists: " . $data['phonenum']);
                echo 'phone_already';
            }
            exit;
        }

        // Handle profile image upload
        $profile_image = 'default.jpg'; // Default image name
        
        if(isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
            error_log("Processing profile image upload");
            
            $img = uploadUserImage($_FILES['profile']);
            
            if($img == 'inv_img'){
                error_log("Invalid image format");
                echo 'inv_img';
                exit;
            }
            else if($img == 'inv_size'){
                error_log("Image size too large");
                echo 'inv_size';
                exit;
            }
            else if($img == 'upd_failed'){
                error_log("Image upload failed");
                echo 'upd_failed';
                exit;
            }
            else {
                $profile_image = $img;
                error_log("Image uploaded successfully: " . $profile_image);
            }
        } else {
            error_log("No profile image uploaded or upload error");
        }

        // Hash password for security
        $hashed_password = password_hash($data['pass'], PASSWORD_DEFAULT);
        error_log("Password hashed successfully");

        // Insert user information into the database
        $query = "INSERT INTO `user_cred` (`name`, `email`, `phonenum`, `address`, `pincode`, `dob`, `password`, `profile`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $values = [$data['name'], $data['email'], $data['phonenum'], $data['address'], $data['pincode'], $data['dob'], $hashed_password, $profile_image];
        
        error_log("Attempting database insert");
        $result = insert($query, $values, 'ssssssss');
        
        if($result) {
            error_log("Registration successful for email: " . $data['email']);
            echo 'registration_success';
        } else {
            error_log("Database insert failed");
            // If database insert failed, delete uploaded image
            if($profile_image != 'default.jpg') {
                deleteImage($profile_image, USERS_FOLDER);
                error_log("Cleaned up uploaded image due to database failure");
            }
            echo 'registration_failed';
        }
        
    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        echo 'registration_failed';
    }
    exit;
}

if(isset($_POST['login'])) {
    try {
        error_log("Login process started");
        
        $data = filteration($_POST);

        // Validate required fields
        if(empty($data['email_mob']) || empty($data['pass'])) {
            error_log("Login: Missing required fields");
            echo 'missing_fields';
            exit;
        }

        // Check if user exists
        $query = "SELECT * FROM `user_cred` WHERE `email` = ? OR `phonenum` = ? LIMIT 1";
        $values = [$data['email_mob'], $data['email_mob']];
        $res = select($query, $values, "ss");

        if(mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            error_log("User found: " . $row['email']);
            
            // Verify password (support both hashed and plain text for backward compatibility)
            $password_valid = false;
            if(password_verify($data['pass'], $row['password'])) {
                $password_valid = true;
                error_log("Password verified with hash");
            } else if($data['pass'] == $row['password']) {
                // For existing plain text passwords
                $password_valid = true;
                error_log("Password verified with plain text (legacy)");
                
                // Update to hashed password
                $hashed_password = password_hash($data['pass'], PASSWORD_DEFAULT);
                $update_query = "UPDATE `user_cred` SET `password` = ? WHERE `id` = ?";
                update($update_query, [$hashed_password, $row['id']], 'si');
                error_log("Updated plain text password to hash");
            }
            
            if($password_valid) {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $row['id'];
                $_SESSION['uName'] = $row['name'];
                $_SESSION['uPic'] = $row['profile'];
                
                error_log("Login successful for user ID: " . $row['id']);
                echo 'login_success';
            } else {
                error_log("Invalid password for user: " . $data['email_mob']);
                echo 'invalid_password';
            }
        } else {
            error_log("User not found: " . $data['email_mob']);
            echo 'invalid_email_mob';
        }
        
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo 'login_failed';
    }
    exit;
}

if(isset($_POST['recover_user'])) {
    try {
        error_log("Password recovery process started");
        
        $data = filteration($_POST);
        
        $query = "SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1";
        $values = [$data['email'], $data['token'], date("Y-m-d")];
        $res = select($query, $values, 'sss');
        
        if(mysqli_num_rows($res) == 1) {
            $hashed_password = password_hash($data['pass'], PASSWORD_DEFAULT);
            
            $update_query = "UPDATE `user_cred` SET `password`=?, `token`=?, `t_expire`=? WHERE `email`=? LIMIT 1";
            $update_values = [$hashed_password, null, null, $data['email']];
            
            if(update($update_query, $update_values, 'ssss')) {
                error_log("Password recovery successful for: " . $data['email']);
                echo 'success';
            } else {
                error_log("Password recovery database update failed");
                echo 'failed';
            }
        } else {
            error_log("Invalid recovery token or expired");
            echo 'failed';
        }
        
    } catch (Exception $e) {
        error_log("Password recovery error: " . $e->getMessage());
        echo 'failed';
    }
    exit;
}

// If no valid action is found
error_log("No valid action found in POST data");
echo 'invalid_action';
?>