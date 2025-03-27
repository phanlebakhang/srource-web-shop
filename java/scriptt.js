// script.js
let currentIndex = 0;

const images = document.querySelectorAll(".carousel-images img");
const totalImages = images.length;

function showSlide(index) {
  const carouselImages = document.querySelector(".carousel-images");
  const offset = -index * 100; // 100% vì mỗi hình ảnh chiếm toàn bộ chiều rộng
  carouselImages.style.transform = `translateX(${offset}%)`;
}

function nextSlide() {
  currentIndex = (currentIndex + 1) % totalImages;
  showSlide(currentIndex);
}

function prevSlide() {
  currentIndex = (currentIndex - 1 + totalImages) % totalImages;
  showSlide(currentIndex);
}

// Tự động chuyển hình sau mỗi 3 giây
setInterval(nextSlide, 3000);

// Hiển thị slide đầu tiên
showSlide(currentIndex);
