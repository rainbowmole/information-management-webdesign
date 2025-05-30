document.addEventListener('DOMContentLoaded', () => {
	// === Sidebar Menu Active Toggle ===
	const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');
	allSideMenu.forEach(item => {
		const li = item.parentElement;
		item.addEventListener('click', () => {
			allSideMenu.forEach(i => i.parentElement.classList.remove('active'));
			li.classList.add('active');
		});
	});

	// === Toggle Sidebar ===
	const menuBar = document.querySelector('#content nav .bx.bx-menu');
	const sidebar = document.getElementById('sidebar');
	menuBar.onclick = () => {
		sidebar.classList.toggle('hide');
	};

	// === Dark Mode Toggle ===
	const switchMode = document.getElementById('switch-mode');
	if (switchMode) {
		switchMode.addEventListener('change', function () {
			document.body.classList.toggle('dark', this.checked);
		});
	}

	// === Sidebar Navigation Section Switching ===
	document.querySelectorAll('#sidebar .side-menu a').forEach(link => {
		link.addEventListener('click', function (e) {
			e.preventDefault();
			document.querySelectorAll('#sidebar .side-menu li').forEach(li => li.classList.remove('active'));
			this.parentElement.classList.add('active');

			document.querySelectorAll('.main-section').forEach(section => section.style.display = 'none');
			const targetId = this.getAttribute('data-section');
			const target = document.getElementById(targetId);
			if (target) target.style.display = 'block';
		});
	});

	// === Add Product: Category Toggle Logic ===
	const categorySelect = document.getElementById('category_select');
	const addonWrapper = document.getElementById('addon_type_wrapper');
	const descriptionWrapper = document.getElementById('description_wrapper');

	categorySelect.addEventListener('change', function () {
		const selected = this.value;
		if (selected === 'addons') {
			addonWrapper.style.display = 'block';
			descriptionWrapper.style.display = 'none';
		} else {
			addonWrapper.style.display = 'none';
			descriptionWrapper.style.display = (selected === 'base_foods') ? 'block' : 'none';
		}
	});

	// === Price Input Buttons ===
	const priceInput = document.getElementById('price_input');
	const increaseBtn = document.getElementById('increase_price');
	const decreaseBtn = document.getElementById('decrease_price');

	increaseBtn?.addEventListener('click', () => {
		let value = parseFloat(priceInput.value) || 0;
		value += 0.25;
		priceInput.value = value.toFixed(2);
	});

	decreaseBtn?.addEventListener('click', () => {
		let value = parseFloat(priceInput.value) || 0;
		value = Math.max(0, value - 0.25);
		priceInput.value = value.toFixed(2);
	});

	// === Custom File Input Display ===
	const fileInput = document.getElementById('image_input');
	const fileTrigger = document.getElementById('image_trigger');
	const fileNameDisplay = document.getElementById('file_name');

	fileTrigger?.addEventListener('click', () => {
		fileInput.click();
	});

	fileInput?.addEventListener('change', () => {
		const file = fileInput.files[0];
		fileNameDisplay.textContent = file ? file.name : 'No file chosen';
	});
});

// === Store Tab Navigation ===
function showCategory(category) {
	const categories = ['base', 'beverage', 'addon', 'add'];
	categories.forEach(cat => {
		document.getElementById(cat).style.display = (cat === category) ? 'block' : 'none';
	});

	document.querySelectorAll('.store-tab').forEach(tab => {
		tab.classList.remove('active');
	});

	const btnMap = {
		base: 'Base Foods',
		beverage: 'Beverages',
		addon: 'Add Ons',
		add: 'Add Product'
	};

	document.querySelectorAll('.store-tab').forEach(tab => {
		if (tab.textContent.trim() === btnMap[category]) {
			tab.classList.add('active');
		}
	});
}
