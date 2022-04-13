const bannerElement = document.querySelector('#banner-items');
const bannerLeftArrow = document.querySelector('#banner--controls-left');
const bannerRightArrow = document.querySelector('#banner--controls-right');
let current = 0;

function santotoBanner( type) {
	const numElme = document.querySelectorAll('.banner--item').length;

	if(type == 'auto' || type == 'forward') {
		if(current < numElme ) {
			bannerElement.style.transform = `translateX(-${current}00%)`;
			current++
		}
		else  {
			bannerElement.style.transform = 'translateX(0%)';
			current = 0;
		}
	}

	else if(type == 'backward') {
		
		current = current - 2

		if(current === -1) {
			current = numElme - 1;
			bannerElement.style.transform = `translateX(-${current}00%)`;
		} else if (current === 0) {
			bannerElement.style.transform = 'translateX(0%)';
			current++;
		} else if(current < numElme ) {
			bannerElement.style.transform = `translateX(-${current}00%)`;
			current++
		}  
		
	}	

}

function start() {
	santotoBanner('auto');
}

start();

setInterval(start, 5000);

bannerLeftArrow.addEventListener('click', event => {
	santotoBanner('backward');
})
bannerRightArrow.addEventListener('click', event => {
	santotoBanner('forward');
})