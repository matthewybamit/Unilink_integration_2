document.addEventListener('DOMContentLoaded', function () {
    const imageUpload = document.getElementById('image-upload');
    const sliderContainer = document.getElementById('image-preview-slider-container');
    const slider = document.getElementById('image-preview-slider');
    const indicator = document.getElementById('image-preview-indicator');

    imageUpload.addEventListener('change', function () {
        // Clear existing previews
        slider.innerHTML = '';

        const files = Array.from(imageUpload.files);
        const maxVisibleImages = 3;

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewDiv = document.createElement('div');
                previewDiv.classList.add('image-preview');

                const img = document.createElement('img');
                img.src = e.target.result;

                const removeBtn = document.createElement('button');
                removeBtn.textContent = '×';
                removeBtn.classList.add('remove-image-btn');
                removeBtn.dataset.index = index;

                removeBtn.addEventListener('click', function () {
                    const updatedFiles = files.filter((_, i) => i !== index);
                    const dataTransfer = new DataTransfer();
                    updatedFiles.forEach((file) => dataTransfer.items.add(file));
                    imageUpload.files = dataTransfer.files;

                    slider.removeChild(previewDiv);
                    updateSliderIndicator(updatedFiles);
                });

                previewDiv.appendChild(img);
                previewDiv.appendChild(removeBtn);
                slider.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });

        updateSliderIndicator(files);
    });

    function updateSliderIndicator(files) {
        if (files.length > 3) {
            indicator.style.display = 'block';
            indicator.textContent = `+${files.length - 3}`;
        } else {
            indicator.style.display = 'none';
        }

        const slideWidth = sliderContainer.offsetWidth;
        slider.style.transform = `translateX(-${Math.min(files.length - 3, 0) * slideWidth}px)`;
    }
});
