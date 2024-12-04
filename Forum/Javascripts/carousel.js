let slideIndex = 0;
const slides = document.querySelectorAll('.carousel-content');
const totalSlides = slides.length;
const carouselSlide = document.getElementById('carouselSlide');
const dots = document.querySelectorAll('.dot');

function showSlides() {
    // Ensure the index stays in bounds
    if (slideIndex >= totalSlides) { 
        slideIndex = 0; 
    } else if (slideIndex < 0) {
        slideIndex = totalSlides - 1;
    }
    
    // Move the carousel slide by adjusting the transform property
    carouselSlide.style.transform = `translateX(${-slideIndex * 100}%)`;

    // Update dot indicator
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === slideIndex);
    });
}

function nextSlide() {
    slideIndex++;
    showSlides();
}

function currentSlide(n) {
    slideIndex = n - 1;
    showSlides();
}

// Initial display of slides
showSlides();

// Auto-slide every 5 seconds
let autoSlide = setInterval(nextSlide, 5000);

// Stop auto-slide on hover and resume after hover
const carousel = document.querySelector('.carousel');
carousel.addEventListener('mouseover', () => {
    clearInterval(autoSlide);
});
carousel.addEventListener('mouseout', () => {
    autoSlide = setInterval(nextSlide, 5000);
});
