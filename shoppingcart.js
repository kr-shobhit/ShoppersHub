document.addEventListener("DOMContentLoaded", function () {
  console.log("JavaScript file loaded");
  function updateTotal() {
    let subtotal = 0;
    document.querySelectorAll(".cart-item").forEach((item) => {
      const priceText = item
        .querySelector(".item-price")
        .textContent.replace("₹", "")
        .replace(",", "");
      const price = parseFloat(priceText);
      const quantity = parseInt(item.querySelector(".quantity").textContent);
      subtotal += price * quantity;
    });
    const tax = subtotal * 0.1; // 10% tax
    const deliveryCharges = 0; // Delivery is free
    const total = subtotal + tax + deliveryCharges;
    console.log("Subtotal:", subtotal);
    console.log("Tax:", tax);
    console.log("Total:", total);
    const priceDetails = document.querySelector(".price-details");
    if (priceDetails) {
      priceDetails.querySelector(
        ".price-item:nth-of-type(1) .amount"
      ).textContent = `₹${subtotal.toFixed(2)}`;
      priceDetails.querySelector(
        ".price-item:nth-of-type(2) .amount"
      ).textContent = "FREE"; // Delivery is free
      priceDetails.querySelector(
        ".price-item:nth-of-type(3) .amount"
      ).textContent = `₹${tax.toFixed(2)}`;
      priceDetails.querySelector(
        ".price-item.total .amount"
      ).textContent = `₹${total.toFixed(2)}`;
    }
  }
  function increaseQuantity(button) {
    let quantityElement = button.parentElement.querySelector(".quantity");
    let quantity = parseInt(quantityElement.textContent);
    quantityElement.textContent = quantity + 1;
    updateTotal(); 
  }
  function decreaseQuantity(button) {
    let quantityElement = button.parentElement.querySelector(".quantity");
    let quantity = parseInt(quantityElement.textContent);
    if (quantity > 1) {
      quantityElement.textContent = quantity - 1;
      updateTotal();
    }
  }
  document.querySelectorAll(".quantity-btn").forEach((button) => {
    button.addEventListener("click", function () {
      console.log("Button clicked:", button.textContent); 
      if (button.classList.contains("increase")) {
        increaseQuantity(button);
      } else if (button.classList.contains("decrease")) {
        decreaseQuantity(button);
      }
    });
  });
  updateTotal();
});