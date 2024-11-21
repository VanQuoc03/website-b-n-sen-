document.addEventListener("DOMContentLoaded", function() {
    const categoryMenu = document.querySelector(".hero__categories__all");
    const categoryList = document.querySelector(".hero__categories ul");
    const priceSection = document.querySelector(".sidebar__item");

    categoryMenu.addEventListener("click", function() {
        categoryList.classList.toggle("expanded");

        // Kiểm tra nếu menu đã mở rộng thì tính toán chiều cao và đẩy phần Price xuống
        if (categoryList.classList.contains("expanded")) {
            const categoryHeight = categoryList.scrollHeight;
            priceSection.style.marginTop = `${categoryHeight}px`;
        } else {
            priceSection.style.marginTop = "0";
        }
    });
});
$(function () {
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 400000,
        values: [0, 400000],
        slide: function (event, ui) {
            $("#minamount").val(ui.values[0]);
            $("#maxamount").val(ui.values[1]);
        }
    });
    // Gán giá trị mặc định vào input khi load trang
    $("#minamount").val($("#slider-range").slider("values", 0));
    $("#maxamount").val($("#slider-range").slider("values", 1));
});

$("#apply-filter").on("click", function() {
    const minPrice = $("#minamount").val() || 0;
    const maxPrice = $("#maxamount").val() || 0;

    // Kiểm tra giá trị hợp lệ
    if (parseInt(minPrice) < 0 || parseInt(maxPrice) < 0) {
        alert("Giá không hợp lệ!");
        return;
    }

    // Tạo URL mới
    const currentUrl = window.location.href.split("?")[0];
    const params = new URLSearchParams(window.location.search);
    params.set("minPrice", minPrice);
    params.set("maxPrice", maxPrice);

    // Chuyển hướng đến URL mới
    window.location.href = `${currentUrl}?${params.toString()}`;
});
$(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const minPrice = urlParams.get("minPrice") || 0;
    const maxPrice = urlParams.get("maxPrice") || 400000; // Giá trị max mặc định, thay đổi theo nhu cầu

    // Khởi tạo thanh trượt
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 400000,
        values: [minPrice, maxPrice],
        slide: function (event, ui) {
            $("#minamount").val(ui.values[0]);
            $("#maxamount").val(ui.values[1]);
        },
    });

    // Gán giá trị vào input khi load trang
    $("#minamount").val(minPrice);
    $("#maxamount").val(maxPrice);
});
