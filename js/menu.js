let qty = 1;

//changes on line 4 up to line 39
function openModal(title, image, description, price, id) { //refer to menu.php line 17 it gets the value of the item
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalImage').src = image;
    document.getElementById('modalDesc').innerText = description;
    document.getElementById('modalPrice').innerText = parseFloat(price).toFixed(2);
    document.getElementById('qtyValue').innerText = qty = 1;
    document.getElementById('itemModal').style.display = 'flex';

    const checklist = document.getElementById('checklist');
    checklist.innerHTML = 'Loading add-ons...';

    // Fetch dynamic add-ons from the same file
    fetch(`fetch_addons.php?basefood_id=${id}`) //the basefood_id == id(refer to line4) and table == addonTable(refer to line 4) this will pass on fetch_addons.php
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log("receive data:", data);
            checklist.innerHTML = '';

            if (data.length === 0) {
                checklist.innerHTML = '<em>No add-ons available</em>';
                return;
            }
            data.forEach(addon => { //remember the addon list in line 19 of fetch_addons.php, this is to iterate each one of them as a list of checkbox
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `addon${addon.id}`;
                checkbox.name = 'addons[]';
                checkbox.value = addon.id;

                const label = document.createElement('label');
                label.htmlFor = `addon${addon.id}`;
                label.textContent = `Add ${addon.name} ${addon.price == 0 ? '(free)' : `(₱${addon.price})`}`;
                
                checklist.appendChild(checkbox);
                checklist.appendChild(label);
            });
        })
}

function closeModal() {
    document.getElementById('itemModal').style.display = 'none';
}

function changeQty(amount) {
    qty = Math.max(1, qty + amount);
    document.getElementById('qtyValue').innerText = qty;
}

// CART MODAL
const cartBtn = document.getElementById("cartBtn");
const cartModal = document.getElementById("cartModal");

if (cartBtn) {
    cartBtn.addEventListener("click", showCart);
}

function showCart() {
    cartModal.style.display = "flex";
}

function closeCart() {
    console.log("closeCart called");
    cartModal.style.display = "none";
}

// Click outside modal to close
window.addEventListener("click", (e) => {
    if (e.target === cartModal) {
        closeCart();
    }
});

// One working listener
const closeCartBtn = cartModal.querySelector(".close");
if (closeCartBtn) {
    closeCartBtn.addEventListener("click", closeCart);
}

function addToCart() {
    closeModal();

    const cartItems = document.getElementById('cartItems');
    const emptyText = cartItems.querySelector('p');
    if (emptyText) emptyText.remove();

    const name = document.getElementById('modalTitle').innerText;
    const price = parseFloat(document.getElementById('modalPrice').innerText);
    const quantity = parseInt(document.getElementById('qtyValue').innerText);
    const image = document.getElementById('modalImage').src;

    const selectedAddons = Array.from(document.querySelectorAll('.addons input:checked'));
    const addonNames = selectedAddons.map(a => {
        const label = document.querySelector(`label[for="${a.id}"]`);
        const raw = label ? label.textContent.trim() : '';
        return raw.replace(/^Add\s+/i, '').trim();
    });

    const addonTotal = selectedAddons.reduce((sum, addon) =>{
        const addonID = addon.id;
        return sum + (addonPriceMap.get(addonID) || 0);
    }, 0);

    const item = document.createElement('div')
    item.classname = 'cart-item';

    item.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="${image}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
            <div style="flex: 1;">
                <div><strong>${name}</strong></div>
                ${addonNames.length ? `<div style="font-size: 0.9em; color: #555;">Add-ons: ${addonNames.join(', ')}</div>` : ''}
            </div>
            <button onclick="this.parentElement.parentElement.remove(); updateCartTotal()" style="background: none; border: none; color: red; cursor: pointer;">✖</button>
        </div>
    `;

    item.dataset.price = price;
    item.dataset.qty = quantity;
    item.dataset.addon = addonTotal;

    cartItems.appendChild(item);
    updateCartTotal();

    const notifContainer = document.getElementById('notifContainer');
    const notif = document.createElement('div');
    notif.className = 'notif';
    notif.innerText = '✅ Item added to cart!';
    notifContainer.appendChild(notif);

    setTimeout(() => {
        notif.remove();
    }, 4000);
}

function updateCartTotal() {
    const cartItems = document.querySelectorAll('.cart-item');
    let subtotal = 0;

    cartItems.forEach(item => {
        const price = parseFloat(item.dataset.price);
        const qty = parseInt(item.dataset.qty);
        const addon = parseFloat(item.dataset.addon);
        subtotal += (price + addon) * qty;
    });

    const vat = subtotal * 0.12;
    const shipping = 50;
    const total = subtotal + vat + shipping;

    document.getElementById('cartSubtotal').innerText = `₱${subtotal.toFixed(2)}`;
    document.getElementById('cartVAT').innerText = `₱${vat.toFixed(2)}`;
    document.getElementById('cartTotal').innerText = `₱${total.toFixed(2)}`;
}


//temporary update lang this //para sa pop up notification pag successfull na naka order user
function handleCheckOut(event) {
    event.preventDefault();  // STOP normal form submit (page reload)

    fetch('checkout.php', {
        method: 'POST',
        body: new URLSearchParams(new FormData(event.target))
    })
    .then(res => res.text())
    .then(data => {
        const notifContainer = document.getElementById('notifContainer');
        const notif = document.createElement('div');
        notif.className = 'notif';
        notif.innerText = '✅ Order placed successfully!';
        notifContainer.appendChild(notif);

        setTimeout(() => notif.remove(), 4000);

        // clear cart UI & close modal if you have these functions
        document.getElementById('cartItems').innerHTML = '<p>Your cart is empty.</p>';
        updateCartTotal();
        closeCart();
    })
    .catch(() => alert('Checkout failed.'));
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkoutForm');
    if (form) {
        form.addEventListener('submit', handleCheckOut);
    }
});