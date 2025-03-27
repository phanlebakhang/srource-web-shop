document.addEventListener("DOMContentLoaded", function () {
  const buyButtons = document.querySelectorAll(".buy-button");

  buyButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      alert("Chức năng này sẽ chuyển hướng đến trang chi tiết sản phẩm!");
      // Bạn có thể thêm mã chuyển hướng tại đây nếu cần.
    });
  });

  // Hiệu ứng tìm kiếm
  const searchInput = document.querySelector('input[name="search"]');
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      // Xử lý tìm kiếm theo thời gian thực nếu cần
    });
  }
});
