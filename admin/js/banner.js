const moreItems = document.querySelector('#addgaleryitem');
const itemArea = document.querySelector('#banner--data');
const submitBtn = document.querySelector('#submit');
const bannerItems = document.querySelectorAll('.item');
const max = 3 - bannerItems.length;

if(max === 0) {
	submitBtn.disabled = true;
}

moreItems.addEventListener('click', event => {
	const itemsBanner = document.querySelectorAll('.bannerfoto');
	if(itemsBanner.length < max) {
		itemArea.insertAdjacentHTML('beforeend', `
			<div class="row">
				<label for="banner">Foto</label>
				<input type="file" class="bannerfoto" name="foto[]" accept="image/*">
			</div>
		`)
	}
});


