let slideIndex = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");
let autoSlideInterval; 

function showSlides() {
    slides.forEach(slide => slide.classList.remove("active"));
    dots.forEach(dot => dot.classList.remove("active"));

    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    slides[slideIndex - 1].classList.add("active");
    dots[slideIndex - 1].classList.add("active");
}

function startSlideshow() {
    autoSlideInterval = setInterval(showSlides, 3000); 
}

function currentSlide(index) {
    slideIndex = index - 1;
    clearInterval(autoSlideInterval); 
    showSlides();
    startSlideshow();
}

showSlides();
startSlideshow(); 