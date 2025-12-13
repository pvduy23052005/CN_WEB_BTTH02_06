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

 // 1. XỬ LÝ PREVIEW VIDEO UPLOAD
    document.getElementById('video_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewArea = document.getElementById('video-preview-area');
        const localPlayer = document.getElementById('local-video-player');
        const youtubeImg = document.getElementById('youtube-preview-img');
        const ytIcon = document.getElementById('yt-icon');
        const urlInput = document.getElementById('video_url');

        if (file) {
            // Reset ô nhập link
            urlInput.value = ''; 
            
            // Tạo URL ảo để xem thử video
            const fileURL = URL.createObjectURL(file);
            localPlayer.src = fileURL;
            
            // Hiển thị player, ẩn youtube
            previewArea.style.display = 'block';
            localPlayer.style.display = 'block';
            youtubeImg.style.display = 'none';
            ytIcon.style.display = 'none';
        }
    });

    // 2. XỬ LÝ PREVIEW YOUTUBE LINK
    document.getElementById('video_url').addEventListener('input', function(event) {
        const url = event.target.value;
        const previewArea = document.getElementById('video-preview-area');
        const localPlayer = document.getElementById('local-video-player');
        const youtubeImg = document.getElementById('youtube-preview-img');
        const ytIcon = document.getElementById('yt-icon');
        const fileInput = document.getElementById('video_file');

        // Regex lấy ID Youtube
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);

        if (match && match[2].length == 11) {
            // Reset ô chọn file
            fileInput.value = ''; 
            
            const videoId = match[2];
            youtubeImg.src = 'https://img.youtube.com/vi/' + videoId + '/mqdefault.jpg';
            
            // Hiển thị youtube, ẩn player local
            previewArea.style.display = 'block';
            youtubeImg.style.display = 'block';
            ytIcon.style.display = 'block';
            localPlayer.style.display = 'none';
            localPlayer.pause(); // Dừng video local nếu đang chạy
        } else {
            // Nếu xóa link hoặc link sai thì ẩn preview nếu không có file chọn
            if (!fileInput.value) {
                previewArea.style.display = 'none';
            }
        }
    });

    // 3. XỬ LÝ PREVIEW TÀI LIỆU (ICON + TÊN FILE)
    document.getElementById('document_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewArea = document.getElementById('doc-preview-area');
        const docName = document.getElementById('doc-name');
        const docSize = document.getElementById('doc-size');
        const docIcon = document.getElementById('doc-icon');

        if (file) {
            previewArea.style.display = 'flex';
            docName.textContent = file.name;
            docSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

            // Đổi icon dựa theo đuôi file
            const ext = file.name.split('.').pop().toLowerCase();
            
            // Reset classes
            docIcon.className = 'fas preview-icon';

            if (ext === 'pdf') {
                docIcon.classList.add('fa-file-pdf');
            } else if (['doc', 'docx'].includes(ext)) {
                docIcon.classList.add('fa-file-word');
            } else if (['xls', 'xlsx'].includes(ext)) {
                docIcon.classList.add('fa-file-excel');
            } else if (['ppt', 'pptx'].includes(ext)) {
                docIcon.classList.add('fa-file-powerpoint');
            } else if (['zip', 'rar'].includes(ext)) {
                docIcon.classList.add('fa-file-archive');
            } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
                docIcon.classList.add('fa-image'); // Icon ảnh
                docIcon.style.color = '#0dcaf0';
            } else {
                docIcon.classList.add('fa-file');
            }
        } else {
            previewArea.style.display = 'none';
        }
    });
  // Hàm ẩn vùng hiển thị video cũ khi chọn video mới
    function hideCurrentVideo() {
        const currentVideo = document.getElementById('current-video-display');
        if (currentVideo) currentVideo.style.display = 'none';
    }

    // 1. VIDEO UPLOAD
    document.getElementById('video_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewArea = document.getElementById('video-preview-area');
        const localPlayer = document.getElementById('local-video-player');
        const youtubeImg = document.getElementById('youtube-preview-img');
        const ytIcon = document.getElementById('yt-icon');
        const urlInput = document.getElementById('video_url');

        if (file) {
            hideCurrentVideo(); // Ẩn video cũ
            urlInput.value = ''; 
            
            const fileURL = URL.createObjectURL(file);
            localPlayer.src = fileURL;
            
            previewArea.style.display = 'block';
            localPlayer.style.display = 'block';
            youtubeImg.style.display = 'none';
            ytIcon.style.display = 'none';
        }
    });

    // 2. YOUTUBE LINK
    document.getElementById('video_url').addEventListener('input', function(event) {
        const url = event.target.value;
        const previewArea = document.getElementById('video-preview-area');
        const localPlayer = document.getElementById('local-video-player');
        const youtubeImg = document.getElementById('youtube-preview-img');
        const ytIcon = document.getElementById('yt-icon');
        const fileInput = document.getElementById('video_file');

        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);

        if (match && match[2].length == 11) {
            hideCurrentVideo(); // Ẩn video cũ
            fileInput.value = ''; 
            
            const videoId = match[2];
            youtubeImg.src = 'https://img.youtube.com/vi/' + videoId + '/mqdefault.jpg';
            
            previewArea.style.display = 'block';
            youtubeImg.style.display = 'block';
            ytIcon.style.display = 'block';
            localPlayer.style.display = 'none';
            localPlayer.pause();
        } else if (!fileInput.value) {
            previewArea.style.display = 'none';
            // Nếu xóa hết input và không có file, có thể hiện lại video cũ (tuỳ chọn)
            const currentVideo = document.getElementById('current-video-display');
            if (currentVideo && url === '') currentVideo.style.display = 'block';
        }
    });

    // 3. TÀI LIỆU
    document.getElementById('document_file').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewArea = document.getElementById('doc-preview-area');
        const docName = document.getElementById('doc-name');
        const docSize = document.getElementById('doc-size');
        const docIcon = document.getElementById('doc-icon');

        if (file) {
            previewArea.style.display = 'flex';
            docName.textContent = file.name;
            docSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

            const ext = file.name.split('.').pop().toLowerCase();
            docIcon.className = 'fas preview-icon';

            if (ext === 'pdf') docIcon.classList.add('fa-file-pdf');
            else if (['doc', 'docx'].includes(ext)) docIcon.classList.add('fa-file-word');
            else if (['xls', 'xlsx'].includes(ext)) docIcon.classList.add('fa-file-excel');
            else if (['zip', 'rar'].includes(ext)) docIcon.classList.add('fa-file-archive');
            else docIcon.classList.add('fa-file');
        } else {
            previewArea.style.display = 'none';
        }
    });