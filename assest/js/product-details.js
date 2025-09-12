document.addEventListener('DOMContentLoaded', function () {
  // ------------------- SIZE SELECTION -------------------
  const sizeButtons = document.querySelectorAll('.size-btn');
  sizeButtons.forEach(button => {
    button.addEventListener('click', () => {
      sizeButtons.forEach(btn => btn.classList.remove('selected'));
      button.classList.add('selected');
    });
  });

  // ------------------- READ MORE TOGGLE -------------------
  const desc = document.getElementById('productDescription');
  const btn = document.getElementById('readMoreBtn');
  if (desc && btn) {
    btn.addEventListener('click', function () {
      const parent = desc.closest('.product-details-scrollable');
      if (desc.classList.contains('description-trimmed')) {
        desc.classList.remove('description-trimmed');
        desc.classList.add('description-expanded');
        if (parent) parent.classList.add('expanded');
        btn.textContent = 'Read Less';
      } else {
        desc.classList.add('description-trimmed');
        desc.classList.remove('description-expanded');
        if (parent) parent.classList.remove('expanded');
        btn.textContent = 'Read More....';
      }
    });
  }
});

// ------------------- ADD TO CART -------------------
function addToCart() {
  const { isLoggedIn, productId, hasSizes } = window.PRODUCT_DATA;

  if (!isLoggedIn) {
    alert("Please login to add products to your cart.");
    window.location.href = "user/"; // redirect to login page
    return;
  }

  const selectedSizeBtn = document.querySelector('.size-btn.selected');
  const size = selectedSizeBtn ? selectedSizeBtn.textContent.trim() : '';

  if (!size && hasSizes) {
    alert("Please select a size");
    return;
  }

  fetch('cart_add.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `product_id=${productId}&quantity=1&size=${encodeURIComponent(size)}`
  })
    .then(res => res.text())
    .then(text => {
      console.log("Raw response from server:", text); // debug server response
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        console.error("Invalid JSON response:", e, text);
        alert("Server returned invalid data. Check console.");
        return;
      }

      if (data.status === 'success') {
        alert("✅ " + data.message);
        window.location.href = "cart.php"; // redirect to cart page
      } else {
        alert("❌ " + (data.message || "Something went wrong."));
      }
    })
    .catch(err => {
      console.error("Error adding to cart:", err);
      alert("Something went wrong. Please try again.");
    });
}

// ------------------- BUY NOW -------------------
function buyNow() {
  const { isLoggedIn, productId, hasSizes } = window.PRODUCT_DATA;

  if (!isLoggedIn) {
    alert("Please login to buy now.");
    window.location.href = "user/"; // redirect to login page
    return;
  }

  const selectedSizeBtn = document.querySelector('.size-btn.selected');
  const size = selectedSizeBtn ? selectedSizeBtn.textContent.trim() : '';

  if (!size && hasSizes) {
    alert("Please select a size to continue");
    return;
  }

  let url = `checkout.php?id=${productId}&qty=1`;
  if (size) url += `&size=${encodeURIComponent(size)}`;
  window.location.href = url;
}

// ✅ Expose functions globally for onclick
window.addToCart = addToCart;
window.buyNow = buyNow;
