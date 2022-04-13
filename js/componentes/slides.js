//javascript para controlar sliders
	const leftControl = document.querySelectorAll('.slide--controls-left');
	const rightControl = document.querySelectorAll('.slide--controls-right');

	let currentSlide = 1;

	function santotoSlides(type, slide) {
		const slideNumbers = document.querySelectorAll(`#${slide} .slide--items a`).length;
		const slideElement = document.querySelector(`#${slide}`);

		if(type == 'forward') {
			if(slideNumbers < 4) {
				return;
			}
			if(currentSlide < slideNumbers - 4) {
				slideElement.style.transform = `translateX(calc(-${currentSlide * 25}% - 0.${currentSlide + 2}7rem))`;
				currentSlide++
			}
			else  {
				slideElement.style.transform = 'translateX(0%)';
				currentSlide = 1;
			}
		}

		else if(type == 'backward') {
			currentSlide = currentSlide - 2

			if(currentSlide === -1) {
				if(slideNumbers < 5) {
					return;
				}
				currentSlide = slideNumbers - 5;
				slideElement.style.transform = `translateX(calc(-${currentSlide * 25}% - 0.${currentSlide + 2}7rem))`;
			} else if (currentSlide === 0) {
				slideElement.style.transform = 'translateX(0%)';
				currentSlide++;
			} else if(currentSlide < slideNumbers - 4) {
				slideElement.style.transform = `translateX(calc(-${currentSlide * 25}% - 0.${currentSlide + 2}7rem))`;
				currentSlide++
			}  
			
		}	

	}

	leftControl.forEach(el => {
		el.addEventListener('click', event => {
			slideId = event.target.dataset.id;
			santotoSlides('backward', slideId);
		})
	})

	rightControl.forEach(el => {
		el.addEventListener('click', event => {
			slideId = event.target.dataset.id;
			santotoSlides('forward', slideId);
		})
	})