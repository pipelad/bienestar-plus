(function() {
	const loader = document.querySelector('#load-phase');

	const endLoad = setTimeout(() => {
		loader.innerHTML = '';
		loader.classList.add('hidden');
	}, 2600)
})()