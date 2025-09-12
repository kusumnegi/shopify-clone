function handlePayment(e) {
  const method = document.querySelector('input[name="payment_method"]:checked').value;
  const form = document.getElementById('checkoutForm');
  if (method === 'upi' || method === 'netbanking') {
    e.preventDefault();
    form.action = 'pay_online.php';
    form.submit();
  } else {
    form.action = 'place_order.php';
  }
}