const formulario = document.querySelector('#addform');
const tipo = document.querySelector('#tipo');
const video = document.querySelector('#video');
const galeria = document.querySelector('#galeria');
const itemsGaleria = document.querySelector('#galeria--items');
const masItems = document.querySelector('#addgaleryitem');
const videoUrl = document.querySelector('#video-url');
const videoMini = document.querySelector('#video-min');

let contador = 1;
let maximo = 12;

tipo.addEventListener('change', event => {
	if(event.target.value === 'video') {
		video.classList.remove('disabled');
		video.classList.add('enabled');
		galeria.classList.remove('enabled');
		galeria.classList.add('disabled');
		itemsGaleria.innerHTML = '<div class="row"><div class="galeria--items-item"><label for="img_gal_1">Foto:</label><input disabled name="img_gal[1]" type="file" accept="image/*"></div></div>';
		videoUrl.disabled = false;
		videoMini.disabled = false;
		return
	}
	video.classList.remove('enabled');
	video.classList.add('disabled');
	galeria.classList.remove('disabled');
	galeria.classList.add('enabled');
	itemsGaleria.innerHTML = '<div class="row"><div class="galeria--items-item"><label for="img_gal_1">Foto:</label><input name="img_gal[1]" type="file" accept="image/*"></div></div>';
	videoUrl.disabled = true;
	videoMini.disabled = true;
})

masItems.addEventListener('click', event=> {
	contador++
	if(contador > maximo) {
		masItems.classList.add('hidden');
		return
	}
	itemsGaleria.insertAdjacentHTML('beforeend', `<div class="row"><div class="galeria--items-item"><label for="img_gal">Foto:</label><input required name="img_gal[${contador}]" type="file"></div></div>`);
})

formulario.addEventListener('submit', event => {
	if(tipo.value === 'video') {
		if(videoUrl.value === '') {
			alert('Falta el video');
			event.preventDefault();
			return false;
		}
		if(videoMini.value === '') {
			alert('Falta la miniatura');
			event.preventDefault();
			return false;
		}
	}

	return
});