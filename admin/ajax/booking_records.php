<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");
adminLogin();

if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);

    $limit = 10;
    $page = (isset($frm_data['page']) && is_numeric($frm_data['page'])) ? $frm_data['page'] : 1;
    $start = ($page - 1) * $limit;
    $search = "%{$frm_data['search']}%";

    $query = "SELECT bo.*, bd.* 
              FROM `booking_order` bo
              LEFT JOIN `booking_details` bd 
              ON bo.booking_id = bd.booking_id
              WHERE 
                (
                    (bo.booking_status='booked' AND bo.arrival=1)
                    OR (bo.booking_status='cancelled' AND bo.refund=1)
                    OR (bo.booking_status='payment failed')
                )
                AND (
                    bo.order_id LIKE ?
                    OR bd.phonenum LIKE ?
                    OR bd.user_name LIKE ?
                )
              ORDER BY bo.booking_id DESC";

    $res = select($query, [$search, $search, $search], 'sss');

    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, [$search, $search, $search], 'sss');

    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        $output = json_encode([
            "table_data" => "<b class='text-danger'>Không có dữ liệu!</b>",
            "pagination" => ''
        ]);
        echo $output;
        exit;
    }

    $i = $start + 1;
    $table_data = "";

    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = isset($data['datentime']) ? date("d-m-Y", strtotime($data['datentime'])) : '';
        $checkin = isset($data['check_in']) ? date("d-m-Y", strtotime($data['check_in'])) : '';
        $checkout = isset($data['check_out']) ? date("d-m-Y", strtotime($data['check_out'])) : '';

        if ($data['booking_status'] == 'booked') {
            $status_bg = 'bg-success';
        } elseif ($data['booking_status'] == 'cancelled') {
            $status_bg = 'bg-danger';
        } else {
            $status_bg = 'bg-warning text-dark';
        }

        $user_name = $data['user_name'] ?? 'N/A';
        $phonenum = $data['phonenum'] ?? 'N/A';
        $room_name = $data['room_name'] ?? 'N/A';
        $price = $data['price'] ?? 0;
        $total_pay = $data['total_pay'] ?? 0;

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>
                    <span class='badge bg-primary'>Order ID: {$data['order_id']}</span><br>
                    <b>Name:</b> $user_name<br>
                    <b>Phone No:</b> $phonenum
                </td>
                <td>
                    <b>Room:</b> $room_name<br>
                    <b>Price:</b> $price VND
                </td>
                <td>
                    <b>Check-in:</b> $checkin<br>
                    <b>Check-out:</b> $checkout<br>
                    <b>Amount:</b> $total_pay VND
                </td>
                <td>
                    <span class='badge $status_bg'>{$data['booking_status']}</span>
                </td>
                <td>
                    <button type='button' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                        <i class='bi bi-file-earmark-arrow-down-fill'></i>
                    </button>
                </td>
            </tr>
        ";
        $i++;
    }

    $pagination = "";
    if ($total_rows > $limit) {
        $total_pages = ceil($total_rows / $limit);

        // Nút "First"
        if ($page != 1) {
            $pagination .= "<li class='page-item'>
                <button onclick='change_page(1)' class='page-link shadow-none'>First</button>
            </li>";
        }

        // Nút "Prev"
        $prev = $page - 1;
        $disabled = ($page == 1) ? "disabled" : "";
        $pagination .= "<li class='page-item $disabled'>
            <button onclick='change_page($prev)' class='page-link shadow-none'>Prev</button>
        </li>";

        // Nút "Next"
        $next = $page + 1;
        $disabled = ($page == $total_pages) ? "disabled" : "";
        $pagination .= "<li class='page-item $disabled'>
            <button onclick='change_page($next)' class='page-link shadow-none'>Next</button>
        </li>";

        // Nút "Last"
        if ($page != $total_pages) {
            $pagination .= "<li class='page-item'>
                <button onclick='change_page($total_pages)' class='page-link shadow-none'>Last</button>
            </li>";
        }
    }

    $output = json_encode([
        "table_data" => $table_data,
        "pagination" => $pagination
    ]);

    echo $output;
}
?>
