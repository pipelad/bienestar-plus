const modal = document.querySelector('#modal');
const videoItems = document.querySelectorAll('.video-popup');
const fotoItems = document.querySelectorAll('.foto-popup');
const galeriaArray = [];

// crear un array con todas las src de las fotos grandes
fotoItems.forEach(el => {
	galeriaArray.push(el.dataset.id);
})

// funcion para cambiar de fotos
function changeImage(srcIndex) {
	const imgModal = document.querySelector('#img-modal');
	if(imgModal) {
		// en caso que sea la primera foto, enviar a la ultima
		if(srcIndex === -1) {
			renderImage(
				galeriaArray.length - 2,
				galeriaArray[galeriaArray.length -1],
				0
			)
			return;
		}
		// en caso que sea la última foto, enviar a la primera
		if(srcIndex === galeriaArray.length) {
			renderImage(
				galeriaArray.length - 1,
				galeriaArray[0],
				1
			)
			return;
		}
		// todos los casos intermedios
		renderImage(
			srcIndex - 1,
			galeriaArray[srcIndex],
			srcIndex + 1
		)
	}
}

// funcion para renderizar la foto grande en el modal
function renderImage(prev, current, next) {
	modal.innerHTML = `
		<div class="foto-modal">
			<div class="control" onclick="changeImage(${prev})"><img src="images/base/flecha-left.svg"></div>
			<img id="img-modal" src="${current}">
			<div class="control" onclick="changeImage(${next})"><img src="images/base/flecha-right.svg"></div>
		</div>
	`;
}

// agregar evento click a todos los items de video y que muestren el video correspondiente.
if(videoItems.length !== 0) {
	videoItems.forEach(el => {
		el.addEventListener('click', event => {
			if(event.target.closest('a').dataset.id) {
				modal.innerHTML = `
					<div class="video-modal">
						<div class="video-wrapper">
							<iframe width="560" height="315" src="${event.target.closest('a').dataset.id}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					</div>
				`;
				modal.classList.remove('hidden');
			}
		})
	})
}

// agregar evento click a todos los items de fotos y que llamen a la funcion de renderizar foto grande.
if(fotoItems.length !== 0) {
	fotoItems.forEach(el => {
		el.addEventListener('click', event => {
			if(event.target.closest('a').dataset.id) {
				renderImage(
					Number(event.target.closest('a').dataset.index) - 1,
					event.target.closest('a').dataset.id,
					Number(event.target.closest('a').dataset.index) + 1
				)
				modal.classList.remove('hidden');
			}
		})
	})
}

// funcion para ocultar el modal haciendo click en cualquier lugar menos en los botones de control.
modal.addEventListener('click', event => {
	if(event.target.closest('.control')) {
		return;
	}
	if(!modal.classList.contains('hidden')) {
		modal.classList.add('hidden');
		modal.innerHTML = '';
	}
})

