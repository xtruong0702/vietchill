function booking_analytics(period=1)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/dashboard.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    let data = JSON.parse(this.responseText);

    // Tổng lượt đặt phòng
    document.getElementById('total_bookings').textContent = data.total_bookings;
    document.getElementById('total_amt').textContent = (data.total_amt/1000000).toFixed(2) + ' triệu đồng';

    // Phòng đã cho thuê
    document.getElementById('active_bookings').textContent = data.active_bookings;
    document.getElementById('active_amt').textContent = (data.active_amt/1000000).toFixed(2) + ' triệu đồng';
    
    // Phòng đã hủy
    document.getElementById('cancelled_bookings').textContent = data.cancelled_bookings;
    document.getElementById('cancelled_amt').textContent = (data.cancelled_amt/1000000).toFixed(2) + ' triệu đồng';
    
    // Dịch vụ
    document.getElementById('total_services_booked').textContent = data.total_services_booked;
    document.getElementById('total_service_revenue').textContent = (data.total_service_revenue/1000000).toFixed(2) + ' triệu đồng';
  }

  xhr.send('booking_analytics&period='+period);
}

function user_analytics(period=1)
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST","ajax/dashboard.php",true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function(){
    let data = JSON.parse(this.responseText);

    document.getElementById('total_new_reg').textContent = data.total_new_reg;
    document.getElementById('total_queries').textContent = data.total_queries;
    document.getElementById('total_reviews').textContent = data.total_reviews;
  }

  xhr.send('user_analytics&period='+period);
}

window.onload = function(){
  booking_analytics(4);
  user_analytics(4);
}
