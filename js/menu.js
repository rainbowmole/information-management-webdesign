let qty = 1;
let addonPriceMap = new Map(); //new update
let dynamicShippingFee = 0; //new update
let cart = JSON.parse(localStorage.getItem('cart')) || []; //new update

function openModal(title, image, description, price, id, category_id) { 
    document.getElementById('modalId').value = id; //new update
    document.getElementById('modalCategoryId').value = category_id; //new update
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalImage').src = image;
    document.getElementById('modalDesc').innerText = description;
    document.getElementById('modalPrice').innerText = parseFloat(price).toFixed(2);
    document.getElementById('qtyValue').innerText = qty = 1;
    document.getElementById('itemModal').style.display = 'flex';

    const checklist = document.getElementById('checklist');
    checklist.innerHTML = '<em style="white-space: nowrap;">No add-ons available</em>';

    console.log('category_id:', category_id, typeof category_id); //error checking lang

    // Fetch dynamic add-ons from the same file (new update)
    if (category_id === 1) { //<---------------if the category_id is 1 which is yung basefood natin it will display the addons para to sa menu cards (new update)
        checklist.innerHTML = 'Loading add-ons...';

        fetch(`fetch_addons.php?category_id=${category_id}&basefood_id=${id}`) 
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

                data.forEach(addon => { 
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

if (cartBtn) {
    cartBtn.addEventListener("click", showCart);
}

function showCart() {
    cartModal.style.display = "flex";

    fetch('fetch_shipping_fee.php') //get the shipping fee of the user from the fetch_shipping_fee.php file
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

//add to cart function
function addToCart() {
    closeModal();

    const cartItems = document.getElementById('cartItems');
    const emptyText = cartItems.querySelector('p');
    if (emptyText) emptyText.remove();

    //getting the data for the item
    const id = parseInt(document.getElementById('modalId').value); //(new update)
    const category_id = parseInt(document.getElementById('modalCategoryId').value); //(new update)
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

    const addonTotal = selectedAddons.reduce((sum, addon) => {
        const addonID = addon.id;
        return sum + (addonPriceMap.get(addonID) || 0);
    }, 0);

    const item = document.createElement('div');
    item.className = 'cart-item';

    //variables of the item for the cart
    item.dataset.id = id; 
    item.dataset.price = price;
    item.dataset.qty = quantity;
    item.dataset.addon = addonTotal;
    item.dataset.categoryId = category_id; 

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

    cartItems.appendChild(item);
    updateCartTotal();

    //to notify the user that the itemm is successfully added to the cart
    const notifContainer = document.getElementById('notifContainer');
    const notif = document.createElement('div');
    notif.className = 'notif';
    notif.innerText = '✅ Item added to cart!';
    notifContainer.appendChild(notif);

    saveCartToLocalStorage(); //save the cart to local storage

    setTimeout(() => {
        notif.remove();
    }, 4000);
}

//to save the cart items across the pages, instead of using the initial plan of cart table in a database, it's better to use a local storage instead.
function saveCartToLocalStorage() {
    const cartItems = Array.from(document.querySelectorAll('.cart-item')).map(item => ({
        id: parseInt(item.dataset.id), // ✅ Fixed line
        name: item.querySelector('strong').innerText,
        image: item.querySelector('img').src,
        price: parseFloat(item.dataset.price),
        qty: parseInt(item.dataset.qty),
        addon: parseFloat(item.dataset.addon),
        category_id: parseInt(item.dataset.categoryId),
        addonsText: item.querySelector('div:nth-child(2) div:nth-child(2)')?.innerText || ''
    }));

    localStorage.setItem('cart', JSON.stringify(cartItems));
}

//getting the total amount of the cart items
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

    document.getElementById('cartSubtotal').innerText = `₱${subtotal.toFixed(2)}`; //the total price of the items
    document.getElementById('cartVAT').innerText = `₱${vat.toFixed(2)}`; //the tax for this cart
    document.getElementById('cartTotal').innerText = `₱${total.toFixed(2)}`; //the total price of the items including tax

    saveCartToLocalStorage();//saving the total amount to the local storage 
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
            el.dataset.id = item.id;                  // (new update)
            el.dataset.categoryId = item.category_id; // (new update)
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

        function updateCartTotalWithShipping(shipping) { //adding the shipping fee to the total
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

//PANCHECK OUTT

//the submit button that will pass the data from the local storage to the order, order_items, order_item_addons in the database
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkoutForm');
    if (form) {
        form.addEventListener('submit', handleCheckOut);
    }
});

document.getElementById('checkoutForm').addEventListener('submit', function (e) {
    e.preventDefault(); // prevent form from submitting normally

    const cartData = JSON.parse(localStorage.getItem('cart') || '[]');
    const subtotal = parseFloat(document.getElementById('cartSubtotal').innerText.replace('₱', '')) || 0;
    const vat = parseFloat(document.getElementById('cartVAT').innerText.replace('₱', '')) || 0;
    const shipping = parseFloat(document.getElementById('shippingFee').innerText.replace('₱', '')) || 0;
    const total = subtotal + vat + shipping;

    fetch('checkout.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            cart: cartData,
            total_amount: total
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Order placed successfully! Order ID: ' + data.order_id);
            localStorage.removeItem('cart');
            location.reload(); // or redirect to success page
        } else {
            if (data.error === 'Unauthorized') {
                alert('You must be logged in as a user to place an order.');
                window.location.href = 'login.php'; // manual redirect
            } else {
                console.error(data.error || 'Unknown error');
                alert('Checkout failed: ' + (data.error || 'Unknown error'));
            }
        }
    })
    .catch(err => {
        console.error('Checkout error:', err);
        alert('Checkout failed due to a network error.');
    });
});