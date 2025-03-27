document.addEventListener('DOMContentLoaded', function () {
    // Hiệu ứng fade in cho các phần tử fade-in-element khi trang tải
    function fadeIn() {
        const fadeInElements = document.querySelectorAll('.fade-in-element');
        fadeInElements.forEach(element => {
            element.classList.add('visible');
        });
    }

    // Các hiệu ứng hover cho hình ảnh sản phẩm và danh mục
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.style.transform = 'scale(1.1)';
            item.querySelector('img').style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.2)';
        });
        item.addEventListener('mouseleave', () => {
            item.style.transform = 'scale(1)';
            item.querySelector('img').style.boxShadow = 'none';
        });
    });

    // Thêm hiệu ứng cho các sản phẩm khi hover
    const productItems = document.querySelectorAll('.product-item');
    productItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.style.transform = 'translateY(-10px)';
            item.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
        });
        item.addEventListener('mouseleave', () => {
            item.style.transform = 'translateY(0)';
            item.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        });
    });

    // Thêm hiệu ứng khi cuộn trang
    const scrollElements = document.querySelectorAll('.fade-in-element');
    const fadeInOnScroll = () => {
        const scrollPosition = window.scrollY + window.innerHeight;
        scrollElements.forEach(el => {
            if (scrollPosition > el.offsetTop + 100) {
                el.classList.add('visible');
            }
        });
    };

    // Thêm sự kiện cuộn trang
    window.addEventListener('scroll', fadeInOnScroll);

    // Kích hoạt fadeIn ngay khi trang tải
    fadeIn();

    // Đảm bảo các phần tử được hiển thị khi cuộn
    fadeInOnScroll();
});
