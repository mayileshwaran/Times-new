function showSection(id) {
  document.querySelectorAll('.payment-section').forEach(el => el.style.display = 'none');
  document.getElementById(id).style.display = 'block';
}

function validatePayment() {
  const selected = document.querySelector('input[name="method"]:checked');
  if (!selected) {
    alert("Please select a payment method.");
    return false;
  }

  const method = selected.value;

  if (method === 'upi') {
    const upiId = document.getElementById('upi_id').value.trim();
    if (upiId === "") {
      alert("Please enter your UPI ID.");
      return false;
    }
  }

  if (method === 'card') {
    const cardNumber = document.querySelector('input[name="card_number"]').value.trim();
    const expiry = document.querySelector('input[name="expiry"]').value.trim();
    const cvv = document.querySelector('input[name="cvv"]').value.trim();

    if (!cardNumber || !expiry || !cvv) {
      alert("Please fill in all card details.");
      return false;
    }
  }

  return true;
}
function showSection(method) {
    const sections = document.querySelectorAll('.payment-section');
    sections.forEach(sec => sec.style.display = 'none');
    const active = document.getElementById(method);
    if (active) active.style.display = 'block';
}

function validatePayment() {
    const fullname = document.querySelector('input[name="fullname"]').value.trim();
    const address = document.querySelector('input[name="address"]').value.trim();
    const city = document.querySelector('input[name="city"]').value.trim();
    const pincode = document.querySelector('input[name="pincode"]').value.trim();
    const phone = document.querySelector('input[name="phone"]').value.trim();
    const method = document.querySelector('input[name="method"]:checked');

    if (!fullname || !address || !city || !pincode || !phone) {
        alert("Please fill all address details.");
        return false;
    }

    if (!method) {
        alert("Please select a payment method.");
        return false;
    }

    if (method.value === "upi") {
        const upi = document.getElementById("upi_id").value.trim();
        if (!upi) {
            alert("Please enter your UPI ID.");
            return false;
        }
    }

    if (method.value === "card") {
        const cardNum = document.querySelector('input[name="card_number"]').value.trim();
        const expiry = document.querySelector('input[name="expiry"]').value.trim();
        const cvv = document.querySelector('input[name="cvv"]').value.trim();
        if (!cardNum || !expiry || !cvv) {
            alert("Please fill all card details.");
            return false;
        }
    }

    return true;
}
