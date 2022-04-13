const borrarForm = document.querySelector('#delete-item-form');
const borrarFoto = document.querySelectorAll('.foto-delete');
const masItems = document.querySelector('#addgaleryitem');
const itemsGaleria = document.querySelector('#galeria--items');
const fotosItems = document.querySelectorAll('.row--image');

borrarForm.addEventListener('submit', event=> {
	const confirmar = confirm('¿Está seguro de borrar este item?');
	if(!confirmar) {
		event.preventDefault();
	}
})

borrarFoto.forEach(el => {
	el.addEventListener('submit', event => {
		const confirmar = confirm('¿Está seguro de borrar esta foto?');
		if(!confirmar) {
			event.preventDefault();
		}
	})
})

let contador = 1;
const maximo = 12 - fotosItems.length;

masItems.addEventListener('click', event=> {
	contador++
	if(contador > maximo) {
		masItems.classList.add('hidden');
		return
	}
	itemsGaleria.insertAdjacentHTML('beforeend', `<div class="row"><div class="galeria--items-item"><label for="img_gal">Foto:</label><input name="img_gal[${contador}]" type="file"></div></div>`);
})