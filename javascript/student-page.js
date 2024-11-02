let slideIndex = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");
let autoSlideInterval;

function showSlides() {
    slides.forEach((slide, i) => {
        slide.classList.remove("active");
        dots[i].classList.remove("active");
    });

    slideIndex++;
    if (slideIndex >= slides.length) {
        slideIndex = 0; 
    }

    slides[slideIndex].classList.add("active");
    dots[slideIndex].classList.add("active");
}

function startSlideshow() {
    autoSlideInterval = setInterval(showSlides, 5000);
}

function currentSlide(index) {
    slideIndex = index - 1; 

    clearInterval(autoSlideInterval);

    slides.forEach((slide, i) => {
        slide.classList.remove("active");
        dots[i].classList.remove("active");
    });
    
    slides[slideIndex].classList.add("active");
    dots[slideIndex].classList.add("active");

    startSlideshow();
}

function initSlideshow() {
    slideIndex = 0; 
    showSlides();  
    startSlideshow(); 
}

document.addEventListener("DOMContentLoaded", function() {
    initSlideshow();
});
