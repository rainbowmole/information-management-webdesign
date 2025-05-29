let qty = 1;
let addonPriceMap = new Map();
let dynamicShippingFee = 0;

//changes on line 4 up to line 39
function openModal(title, image, description, price, id, type) { //refer to menu.php line 17 it gets the value of the item
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalImage').src = image;
    document.getElementById('modalDesc').innerText = description;
    document.getElementById('modalPrice').innerText = parseFloat(price).toFixed(2);
    document.getElementById('qtyValue').innerText = qty = 1;
    document.getElementById('itemModal').style.display = 'flex';

    const checklist = document.getElementById('checklist');
    checklist.innerHTML = '<em style="white-space: nowrap;">No add-ons available</em>';

    // Fetch dynamic add-ons from the same file
    if (type === 'basefood') {
        checklist.innerHTML = 'Loading add-ons...';

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

                addonPriceMap.clear();

                data.forEach(addon => { //remember the addon list in line 19 of fetch_addons.php, this is to iterate each one of them as a list of checkbox
                    addonPriceMap.set(`addon${addon.id}`, addon.price);

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
            .catch(err => {
                console.error("Failed to fetch add-ons:", err);
                checklist.innerHTML = '<em>Error loading add-ons</em>';
            });
    }
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

//added
window.addEventListener('load', function () {
    localStorage.removeItem('cart'); // or clear all with localStorage.clear();
});

if (cartBtn) {
    cartBtn.addEventListener("click", showCart);
}

function showCart() {
    cartModal.style.display = "flex";

    fetch('fetch_shipping_fee.php')
        .then(res => res.json())
        .then(data => {
            dynamicShippingFee = parseFloat(data.shipping_fee || 0);
            document.getElementById('shippingFee').innerText = `₱${dynamicShippingFee.toFixed(2)}`;
            updateCartTotal();
        })
        .catch(err => {
            console.error("Failed to fetch shipping fee:", err);
        });
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

    const name = document.getElementById('modalName').textContent.trim();
    const price = parseFloat(document.getElementById('modalPrice').textContent);
    const quantity = parseInt(document.getElementById('modalQuantity').value) || 1;
    const image = document.getElementById('modalImage').src;

    // Assuming you have hidden inputs or data attributes on modal with id and type:
    const itemId = parseInt(document.getElementById('modalId').value); // the product ID
    const itemType = document.getElementById('modalType').value; // 'basefood' or 'beverage'

    const selectedAddons = Array.from(document.querySelectorAll('.addon:checked')).map(addon => {
        return {
            id: parseInt(addon.dataset.id),
            name: addon.dataset.name,
            price: parseFloat(addon.dataset.price),
            quantity: 1 // Or allow user to set quantity per addon
        };
    });

    const addonTotal = selectedAddons.reduce((total, addon) => total + addon.price * addon.quantity, 0);
    const addonNames = selectedAddons.map(addon => `${addon.name} (₱${addon.price})`);

    const cartItem = {
        id: itemId,
        type: itemType, // 'basefood' or 'beverage'
        name,
        image,
        price,
        qty: quantity,
        addons: selectedAddons,
        addonsText: addonNames.length ? `Add-ons: ${addonNames.join(', ')}` : ''
    };

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.push(cartItem);
    localStorage.setItem('cart', JSON.stringify(cart));

    // Save to localStorage: include id, type, and map keys properly
    saveCartToLocalStorage(currentItemId, currentItemType, name, image, price, quantity, addonTotal, selectedAddons, addonNames);

    // Show notification
    const notifContainer = document.getElementById('notifContainer');
    const notif = document.createElement('div');
    notif.className = 'notif';
    notif.innerText = '✅ Item added to cart!';
    notifContainer.appendChild(notif);

    setTimeout(() => {
        notif.remove();
    }, 4000);
}
//to save the cart items across the pages
// Update saveCartToLocalStorage to accept the parameters and include id and type
function saveCartToLocalStorage(currentItemId, currentItemType, name, image, price, quantity, addonTotal, selectedAddons, addonNames) {
    // Get existing cart or empty array
    const existingCart = JSON.parse(localStorage.getItem('cart')) || [];

    // Add new item
    existingCart.push({
        id: currentItemId,
        type: currentItemType,
        name: name,
        image: image,
        price: price,
        qty: quantity,
        addon: addonTotal,
        addonsText: addonNames.length ? `Add-ons: ${addonNames.join(', ')}` : '',
        addons: selectedAddons
    });

    localStorage.setItem('cart', JSON.stringify(existingCart));
    toggleCheckoutButton();
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
    const shipping = dynamicShippingFee || 0;
    const total = subtotal + vat + shipping;

    document.getElementById('cartSubtotal').innerText = `₱${subtotal.toFixed(2)}`;
    document.getElementById('cartVAT').innerText = `₱${vat.toFixed(2)}`;
    document.getElementById('cartTotal').innerText = `₱${total.toFixed(2)}`;
    //line 157
    saveCartToLocalStorage();
}

//for cart item
document.addEventListener('DOMContentLoaded', () => {
    const saved = localStorage.getItem('cart');
    if (saved) {
        const items = JSON.parse(saved);
        const cartItems = document.getElementById('cartItems');
        cartItems.innerHTML = ''; // clear default text

        items.forEach(item => {
            const el = document.createElement('div');
            el.className = 'cart-item';
            el.dataset.price = item.price;
            el.dataset.qty = item.qty;
            el.dataset.addon = item.addon;

            el.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="${item.image}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                    <div style="flex: 1;">
                        <div><strong>${item.name}</strong></div>
                        ${item.addonsText ? `<div style="font-size: 0.9em; color: #555;">${item.addonsText}</div>` : ''}
                    </div>
                    <button onclick="this.parentElement.parentElement.remove(); updateCartTotal(); saveCartToLocalStorage()" style="background: none; border: none; color: red; cursor: pointer;">✖</button>
                </div>
            `;

            cartItems.appendChild(el);
        });

        fetch('fetch_shipping_fee.php')
            .then(res => res.json())
            .then(data => {
                const shipping = data.shipping_fee || 150;
                document.getElementById('shippingFee').innerText = `₱${shipping.toFixed(2)}`;

                // Update total again after setting shipping
                updateCartTotalWithShipping(shipping);
            })
            .catch(err => {
                console.error('Failed to fetch shipping fee:', err);
            });

        function updateCartTotalWithShipping(shipping) {
            const cartItems = document.querySelectorAll('.cart-item');
            let subtotal = 0;

            cartItems.forEach(item => {
                const price = parseFloat(item.dataset.price);
                const qty = parseInt(item.dataset.qty);
                const addon = parseFloat(item.dataset.addon);
                subtotal += (price + addon) * qty;
            });

            const vat = subtotal * 0.12;
            const total = subtotal + vat + shipping;

            document.getElementById('cartSubtotal').innerText = `₱${subtotal.toFixed(2)}`;
            document.getElementById('cartVAT').innerText = `₱${vat.toFixed(2)}`;
            document.getElementById('cartTotal').innerText = `₱${total.toFixed(2)}`;
        }

        updateCartTotal();
    }
});
//added this shii
function prepareCartForCheckout() {
    const rawCart = JSON.parse(localStorage.getItem('cart')) || [];
    return rawCart.map(item => {
        const addons = (item.addons || []).map(addon => ({
            id: addon.addon_id,      // map addon_id -> id
            quantity: 1,             // default 1, change if you support addon qty
            price: addon.price
        }));

        return {
            type: item.type || 'basefood',
            id: item.id || 0,
            quantity: item.qty || 1,
            price: item.price || 0,
            addons: addons
        };
    });
}

//CHECKOUT HANDLER GUYS
//temporary update lang this //para sa pop up notification pag successfull na naka order user
function handleCheckOut(event) {
    event.preventDefault();  // STOP normal form submit (page reload)

    const formData = new FormData(event.target);

    // Get total from cart display
    const totalText = document.getElementById('cartTotal').innerText;
    const totalAmount = parseFloat(totalText.replace('₱', ''));

    formData.append('total_amount', totalAmount);

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

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const cart = prepareCartForCheckout();

    if (cart.length === 0) {
        const notifContainer = document.getElementById('notifContainer');
        const notif = document.createElement('div');
        notif.className = 'notif';
        notif.innerText = '❌ Your cart is empty.';
        notifContainer.appendChild(notif);
        setTimeout(() => notif.remove(), 4000);
        return;
    }

    fetch('checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cart: cart })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Order placed successfully!");
            localStorage.clear(); // Clear cart
            renderCart(); // Refresh cart UI if you have this
            document.getElementById('cartItems').innerHTML = '<p>Your cart is empty.</p>';
            updateCartTotal();
            closeCart();
        } else {
            alert("Error placing order: " + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error("Checkout failed:", error);
        alert("An error occurred.");
    });
});