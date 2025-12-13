const avatarImg = document.getElementById("avatarPreview");
const avatarUpload = document.getElementById("avatarUpload");
const accountBtn = document.querySelector(".account");


accountBtn.addEventListener("click", () => {
    avatarUpload.click();
});

// Khi chọn ảnh → hiển thị lên avatar
avatarUpload.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
        const imageURL = URL.createObjectURL(file);
        avatarImg.src = imageURL;
    }
});

//privirew ảnh
function previewImage(input) {
    var preview = document.getElementById("preview-img");
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(input.files[0]);
    } else {
 
        preview.src = "";
        preview.style.display = "none";
    }
}
