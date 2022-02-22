let sliderImages = document.querySelectorAll(".slide"),
  arrowLeft = document.querySelector("#arrow-left"),
  arrowRight = document.querySelector("#arrow-right"),
  current = 0;


function reset() {
  for (let i = 0; i < sliderImages.length; i++) {
    sliderImages[i].style.display = "none";
  }
}

// photo initial
function startSlide() {
  reset();
  sliderImages[0].style.display = "block";
}

// montre la précédente
function slideLeft() {
  reset();
  sliderImages[current - 1].style.display = "block";
  current--;
}

// montre la prochaine
function slideRight() {
  reset();
  sliderImages[current + 1].style.display = "block";
  current++;
}

// flèche gauche
arrowLeft.addEventListener("click", function () {
  if (current === 0) {
    current = sliderImages.length;
  }
  slideLeft();
});

// flèche droite
arrowRight.addEventListener("click", function () {
  if (current === sliderImages.length - 1) {
    current = -1;
  }
  slideRight();
});

startSlide();
