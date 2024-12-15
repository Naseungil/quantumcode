form.addEventListener("submit", function (event) {
    event.preventDefault(); // 기본 폼 제출 동작 막기

    if (selectedCategories.length === 0) {
        alert("적어도 하나의 카테고리를 선택해주세요.");
    } else {
        // 선택된 카테고리를 숨겨진 필드에 저장
        selectedCategoriesInput.value = JSON.stringify(selectedCategories);

        // AJAX 요청으로 save_categories.php에 데이터 전달
        fetch("../qc/lecture/save_categories.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ categories: selectedCategories }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("카테고리 저장 성공:", data.message);

                // 쿠폰 발급 AJAX 요청
                fetch("/qc/coupon/give_coupon.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({}),
                })
                .then(response => response.json())
                .then(couponData => {
                    if (couponData.success) {
                        console.log("쿠폰 발급 성공:", couponData.message);

                        // 쿠폰 발급 완료 모달 표시
                        const couponModal = new bootstrap.Modal(document.getElementById("couponModal"));
                        couponModal.show();
                    } else {
                        alert("쿠폰 발급 실패: " + couponData.error);
                    }
                });
            } else {
                alert("카테고리 저장 실패: " + data.error);
            }
        })
        .catch(error => {
            console.error("AJAX 요청 오류:", error);
            alert("서버와 통신 중 문제가 발생했습니다.");
        });
    }
});