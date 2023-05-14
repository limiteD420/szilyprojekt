//Hides a div element with the given id
function closeWindow(elementId) {
    document.getElementById(elementId).style.display = "none";
}

function updatePrice(product) {
    // Get the quantity input element for the current product
    var quantityInput = product.querySelector('input[id="form1"]');

    // Get the price element for the current product
    var priceElement = product.querySelector('#productPrice');

    // Get the price value for the current product
    var price = parseFloat(product.querySelector('#prodPrice').value);

    // Calculate the new price based on the quantity
    var newPrice = price * parseFloat(quantityInput.value);

    // Update the price element with the new price
    priceElement.innerText = newPrice + ' HUF';
}

function updateOverallPrice() {
    var elements = document.querySelectorAll('#productPrice');
    var sum = 0;
    
    elements.forEach(function(element) {
      var value = parseFloat(element.innerText);
      sum += value;
    });
    
    document.getElementById('overallPrice').innerText = "Overall price: " + sum;

}
