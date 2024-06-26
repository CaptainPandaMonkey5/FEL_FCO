// for adding item
document.getElementById("addItem_btn").addEventListener("click", function () {
  document.querySelector(".addItem_form").style.display = "flex";
});

document.addEventListener("click", (e) => {
  if (
    !document.querySelector(".addItem_form").contains(e.target) &&
    !document.querySelector("#addItem_btn").contains(e.target)
  ) {
    document.querySelector(".addItem_form").style.display = "none";
  }
});

//for editing item
document.querySelectorAll(".editItem_btn").forEach((button) => {
  button.addEventListener("click", function () {
    let itemID = button.value;

    fetch(`includes/inv_db.php?productID=${itemID}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        document.getElementById("edit_prod_name").value = data.ProductName;
        document.getElementById("edit_prod_category").value = data.Category;
        document.getElementById("edit_prod_price").value = data.Price;
        document.getElementById("edit_prod_description").value =
          data.ProductDesc;
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });

    document.getElementById("edit_prod_id").value = itemID;

    document.querySelector(".editItem_form").style.display = "flex";
  });
});

document.addEventListener("click", (e) => {
  if (
    !document.querySelector(".editItem_form").contains(e.target) &&
    !e.target.matches(".editItem_btn")
  ) {
    document.querySelector(".editItem_form").style.display = "none";
  }
});

// for adding qty item
document.querySelectorAll(".addQty_btn").forEach((button) => {
  button.addEventListener("click", function () {
    let itemID = button.value;
    // Get today's date
    const today = new Date();

    // Format the date to YYYY-MM-DD (required format for input type="date")
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0"); // Adding 1 because January is 0
    const day = String(today.getDate()).padStart(2, "0");

    // Set the value of the input element
    const dateInput = document.getElementById("add_prod_date");
    dateInput.value = `${year}-${month}-${day}`;

    document.getElementById("add_prod_id").setAttribute("value", itemID);

    document.querySelector(".addQty_form").style.display = "flex";

    fetch(`includes/inv_db.php?productID=${itemID}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        document.getElementById("add_prod_name").value = data.ProductName;
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  });
});

document.addEventListener("click", (e) => {
  if (
    !document.querySelector(".addQty_form").contains(e.target) &&
    !e.target.matches(".addQty_btn")
  ) {
    document.querySelector(".addQty_form").style.display = "none";
  }
});

document
  .getElementById("add_prod_qty")
  .addEventListener("input", updateTotalCost);
document
  .getElementById("add_prod_cost")
  .addEventListener("input", updateTotalCost);

function updateTotalCost() {
  // Get quantity and cost per unit values
  const qty = parseFloat(document.getElementById("add_prod_qty").value);
  const cost = parseFloat(document.getElementById("add_prod_cost").value);

  // Compute total cost
  const totalCost = isNaN(qty) || isNaN(cost) ? 0 : qty * cost;

  // Update the total cost input field
  document.getElementById("add_prod_totalcost").value = totalCost.toFixed(2); // Limiting to 2 decimal places
}

// for deducing qty item
document.querySelectorAll(".decQty_btn").forEach((button) => {
  button.addEventListener("click", function () {
    let itemID = button.value;
    // Get today's date
    const today = new Date();

    // Format the date to YYYY-MM-DD (required format for input type="date")
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0"); // Adding 1 because January is 0
    const day = String(today.getDate()).padStart(2, "0");

    // Set the value of the input element
    const dateInput = document.getElementById("dec_prod_date");
    dateInput.value = `${year}-${month}-${day}`;

    document.getElementById("dec_prod_id").setAttribute("value", itemID);

    document.querySelector(".decQty_form").style.display = "flex";

    fetch(`includes/inv_db.php?productID=${itemID}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        document.getElementById("dec_prod_name").value = data.ProductName;
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  });
});

document.addEventListener("click", (e) => {
  if (
    !document.querySelector(".decQty_form").contains(e.target) &&
    !e.target.matches(".decQty_btn")
  ) {
    document.querySelector(".decQty_form").style.display = "none";
  }
});

document.querySelectorAll(".deleteItem_btn").forEach((button) => {
  button.addEventListener("click", function () {
    if (confirm("Are you sure you want to delete this item?")) {
      button.form.submit();
    } else {
      window.location = "Inventory.php";
    }
  });
});
