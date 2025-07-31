 function showSection(method) {
      document.getElementById("upi").style.display = (method === 'upi') ? 'block' : 'none';
      document.getElementById("card").style.display = (method === 'card') ? 'block' : 'none';
      document.getElementById("cod").style.display = (method === 'cod') ? 'block' : 'none';
    }

    function validatePayment() {
      const method = document.querySelector('input[name="method"]:checked');
      if (!method) {
        alert("Please select a payment method.");
        return false;
      }

      if (method.value === 'upi') {
        const upi = document.querySelector('input[name="upi_id"]').value;
        if (!upi.trim()) {
          alert("Please enter your UPI ID.");
          return false;
        }
      }

      if (method.value === 'card') {
        const card = document.querySelector('input[name="card_number"]').value;
        const expiry = document.querySelector('input[name="expiry"]').value;
        const cvv = document.querySelector('input[name="cvv"]').value;
        if (!card.trim() || !expiry.trim() || !cvv.trim()) {
          alert("Please enter all card details.");
          return false;
        }
      }

      return true;
    }
     function confirmDelete(url) {
      if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = url;
      }
    }
  
function editProduct(event, id) {
    event.preventDefault();     // prevent <a href> default
    event.stopPropagation();    // stop bubbling to <a>
    window.location.href = 'update_product.php?id=' + id;
}

function confirmDelete(url) {
    if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = url;
    }
}

