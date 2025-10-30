function adminSignIn() {
  var email = document.getElementById("email");
  var password = document.getElementById("password");

  var form = new FormData();
  form.append("e", email.value);
  form.append("p", password.value);

  var r = new XMLHttpRequest();
  r.onreadystatechange = function () {
    if (r.readyState == 4) {
      var t = r.responseText;
      if (t == "success") {
        window.location = "home.php";
      } else {
        alert(t);
      }
    }
  };

  r.open("POST", "adminSignInProcess.php", true);
  r.send(form);
}

function adminSignOut() {
  fetch("adminSignOutProcess.php")
    .then((res) => res.text())
    .then((t) => {
      if (t.trim() === "Success") {
        location.reload();
      }
    });
}

function searchOrders() {
  const keyword = document.getElementById("searchInput").value;

  fetch("searchOrderProcess.php?query=" + encodeURIComponent(keyword))
    .then((res) => res.json())
    .then((data) => {
      ordersData = data;
      populateOrdersTable(data);
    })
    .catch((error) => console.error("Search error:", error));
}

let ordersData = [];

function loadOrders() {
  fetch("searchOrderProcess.php")
    .then((res) => res.json())
    .then((data) => {
      ordersData = data;
      populateOrdersTable(data);
    })
    .catch((error) => console.error("Load orders error:", error));
}

function populateOrdersTable(data) {
  const tbody = document.getElementById("ordersBody");
  tbody.innerHTML = "";

  if (!data.length) {
    tbody.innerHTML = `
      <tr>
        <td colspan="13" class="text-center text-muted py-4">
          <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
          No pre-orders found
        </td>
      </tr>`;
    return;
  }

  data.forEach((order) => {
    const row = document.createElement("tr");
    row.innerHTML = `
  <td>${order.id}</td>
  <td>${order.customer_name}</td>
  <td>${order.nic}</td>
  <td>${order.city}</td>
  <td>${order.contact}</td>
  <td>${order.no_of_bike}</td>
  <td>${order.model}</td>
  <td>${order.capacity}</td>
  <td>${order.payment}</td>
  <td>${order.remarks || ""}</td>
  <td>${order.location}</td>
  <td>${order.date}</td>
  <td class="text-center">
    <div class="btn-group" role="group">
      <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#callModal" title="Call" onclick="openCallModal(${
        order.id
      })">
        <i class="fas fa-phone"></i>
      </button>
      <button class="btn btn-sm btn-outline-warning" onclick="editOrder(${
        order.id
      })" title="Edit">
        <i class="fas fa-edit"></i>
      </button>
      <button class="btn btn-sm btn-outline-danger" onclick="deleteOrder(${
        order.id
      })" title="Delete">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  </td>
`;
    tbody.appendChild(row);
  });
}

function editOrder(id) {
  const order = ordersData.find((o) => parseInt(o.id) === parseInt(id));
  if (!order) return alert("Order not found!");

  const form = document.getElementById("orderForm");
  if (!form) return;

  form.dataset.editingId = id;

  form.querySelector('[name="customer_name"]').value =
    order.customer_name || "";
  form.querySelector('[name="nic"]').value = order.nic || "";
  form.querySelector('[name="city"]').value = order.city || "";
  form.querySelector('[name="contact"]').value = order.contact || "";
  form.querySelector('[name="NoOfBike"]').value = order.no_of_bike || "1";
  form.querySelector('[name="model"]').value = order.model || "";
  form.querySelector('[name="capacity"]').value = order.capacity || "";
  form.querySelector('[name="payment"]').value = order.payment || "";
  form.querySelector('[name="remarks"]').value = order.remarks || "";
  form.querySelector('[name="location"]').value = order.location || "";

  const modalTitle = document.querySelector("#orderModal .modal-title");
  if (modalTitle)
    modalTitle.innerHTML =
      '<i class="fas fa-edit me-2"></i>Edit Pre-Order #' + id;

  const modal = new bootstrap.Modal(document.getElementById("orderModal"));
  modal.show();
}

function saveOrder(aid) {
  const form = document.getElementById("orderForm");
  const formData = new FormData(form);
  formData.append("aid", aid);
  const isEdit = form.dataset.editingId;

  if (isEdit) formData.append("id", isEdit);

  fetch(isEdit ? "updateOrderProcess.php" : "addOrderProcess.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((result) => {
      if (result.success) {
        alert(
          isEdit ? "Order updated successfully!" : "Order added successfully!"
        );
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("orderModal")
        );
        if (modal) modal.hide();
        form.reset();
        delete form.dataset.editingId;
        loadOrders();
      } else {
        alert("Error saving order: " + (result.message || "Unknown error"));
      }
    })
    .catch((error) => {
      console.error("Save error:", error);
      alert("Error occurred while saving order.");
    });
}

function deleteOrder(id) {
  if (!confirm("Are you sure you want to delete this order?")) return;

  fetch(`deleteOrderProcess.php?id=${id}`)
    .then((res) => res.json())
    .then((result) => {
      if (result.success) {
        alert("Order deleted successfully!");
        loadOrders();
      } else {
        alert("Failed to delete order: " + (result.message || "Unknown error"));
      }
    })
    .catch((error) => {
      console.error("Delete error:", error);
      alert("Network error occurred while deleting order.");
    });
}

document.addEventListener("DOMContentLoaded", () => {
  const orderModal = document.getElementById("orderModal");
  if (orderModal) {
    orderModal.addEventListener("hidden.bs.modal", () => {
      const form = document.getElementById("orderForm");
      if (form) {
        form.reset();
        delete form.dataset.editingId;
        const modalTitle = document.querySelector("#orderModal .modal-title");
        if (modalTitle) {
          modalTitle.innerHTML =
            '<i class="fas fa-plus me-2"></i>Add New Pre-Order';
        }
      }
    });
  }
  loadOrders();
});

function openCallModal(orderId) {
  window.currentOrderId = orderId;

  [
    "answered1",
    "answered2",
    "answered3",
    "notanswered1",
    "notanswered2",
    "notanswered3",
  ].forEach((id) => (document.getElementById(id).checked = false));

  fetch("getCallRecordsProcess.php?order_id=" + orderId)
    .then((res) => res.json())
    .then((data) => {
      if (data.success && data.data) {
        const d = data.data;

        document.getElementById("answered1").checked = d.answared_1 == 1;
        document.getElementById("answered2").checked = d.answared_2 == 1;
        document.getElementById("answered3").checked = d.answared_3 == 1;
        document.getElementById("notanswered1").checked = d.not_answared_1 == 1;
        document.getElementById("notanswered2").checked = d.not_answared_2 == 1;
        document.getElementById("notanswered3").checked = d.not_answared_3 == 1;
      }
    })
    .catch((err) => console.error("Load error:", err));
}

function saveCallStatus() {
  const orderId = window.currentOrderId;
  if (!orderId) {
    alert("No order selected.");
    return;
  }

  // Get checkbox values (1 = checked, 0 = not checked)
  const ans1 = document.getElementById("answered1").checked ? 1 : 0;
  const ans2 = document.getElementById("answered2").checked ? 1 : 0;
  const ans3 = document.getElementById("answered3").checked ? 1 : 0;
  const notAns1 = document.getElementById("notanswered1").checked ? 1 : 0;
  const notAns2 = document.getElementById("notanswered2").checked ? 1 : 0;
  const notAns3 = document.getElementById("notanswered3").checked ? 1 : 0;

  fetch("saveCallStatusProcess.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      action: "save_call",
      order_id: orderId,
      ans1: ans1,
      ans2: ans2,
      ans3: ans3,
      not_ans1: notAns1,
      not_ans2: notAns2,
      not_ans3: notAns3,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        alert("Call status saved successfully!");
        // Close modal
        const modalEl = document.getElementById("callModal");
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((err) => {
      console.error("Fetch error:", err);
      alert("Failed to save call status.");
    });
}
