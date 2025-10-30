<?php
session_start();
if (isset($_SESSION["a"])) {
  $id = $_SESSION["a"]['id'];
  require_once 'connection/connection.php';
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honda Dio Pre-Order Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
  </head>

  <body>
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <span class="navbar-brand">
          <i class="fas fa-motorcycle me-2"></i>Honda Dio Admin System
        </span>
        <a class="btn btn-outline-light btn-sm" onclick="adminSignOut();">
          <i class="fas fa-sign-out-alt me-1"></i>Logout
        </a>
      </div>
    </nav>
    <div class="container my-2">
      <!-- Stats Row -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="stats-card">
            <h5><i class="fas fa-shopping-cart me-2"></i>Total Pre-Orders</h5>
            <?php
            $orders = Database::search("SELECT count(id) as total FROM `order`");
            $data = $orders->fetch_assoc();
            ?>
            <h2><?php echo $data['total'] ?></h2>
          </div>
        </div>
        <div class="col-md-6">
          <div class="stats-card">
            <?php
            $torders = Database::search("SELECT count(id) as total FROM `order` WHERE DATE(`date`) = CURDATE() ");
            $tdata = $torders->fetch_assoc();
            ?>
            <h5><i class="fas fa-calendar-alt me-2"></i>Today's Orders</h5>
            <h2><?php echo $tdata['total'] ?></h2>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#orderModal">
                <i class="fas fa-plus me-2"></i>Add New Pre-Order
              </button>
              <button class="btn btn-outline-secondary ms-2" onclick=window.location.reload();>
                <i class="fas fa-refresh me-2"></i>Refresh
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders Table with Search -->
      <div class="row mb-3">
        <div class="col-md-4 ms-auto">
          <input type="text" id="searchInput" class="form-control" placeholder="Search by name, NIC, city..." oninput="searchOrders()">
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0"><i class="fas fa-list me-2"></i>Pre-Orders List</h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" id="ordersTable">
                  <thead>
                    <tr>
                      <th>Order #</th>
                      <th>Customer Name</th>
                      <th>NIC Number</th>
                      <th>City</th>
                      <th>Contact</th>
                      <th>No Of Bike</th>
                      <th>Model</th>
                      <th>Capacity</th>
                      <th>Payment</th>
                      <th>Remarks</th>
                      <th>Location</th>
                      <th>Date</th>
                      <th>Options</th>
                    </tr>
                  </thead>
                  <tbody id="ordersBody">
                    <tr>
                      <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        No pre-orders found
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Add/Edit Order Modal -->
      <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New Pre-Order</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form id="orderForm">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Customer Name *</label>
                    <input type="text" name="customer_name" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">NIC/ID Number *</label>
                    <input type="text" name="nic" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">City *</label>
                    <input type="text" name="city" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Number *</label>
                    <input type="tel" name="contact" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">No Of Bike *</label>
                    <select name="NoOfBike" class="form-control" required>
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Model *</label>
                    <select name="model" class="form-control" required>
                      <option value="" disabled selected>Select Model</option>
                      <option value="Digital">Digital</option>
                      <option value="Analog">Analog</option>
                    </select>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label">Capacity *</label>
                    <select name="capacity" class="form-control" required>
                      <option value="" disabled selected>Select Capacity</option>
                      <option value="110cc">110cc</option>
                      <option value="125cc">125cc</option>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Type *</label>
                    <select class="form-select" name="payment" required>
                      <option value="cash">Cash</option>
                      <option value="lease">Lease</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Remarks</label>
                    <input type="text" name="remarks" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Location *</label>
                    <select class="form-select" name="location" required>
                      <option value="Ja-Ela">Ja-Ela</option>
                      <option value="Rattanapitiya">Rattanapitiya</option>
                      <option value="Maradana">Maradana</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Date *</label>
                    <input type="text" name="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" required readonly>
                  </div>
                </div>
              </form>

            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-primary" onclick="saveOrder(<?php echo $id; ?>);"><i class="fas fa-save me-2"></i>Save Order</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Call Section Modal -->
      <div class="modal fade" id="callModal" tabindex="-1" aria-labelledby="callModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title" id="callModalLabel"><i class="fas fa-phone me-2"></i>Call Section</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <!-- Answered Call Section -->
              <h6 class="text-success"><i class="fas fa-check-circle me-2"></i>Answered Call</h6>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="answered1">
                <label class="form-check-label" for="answered1">Attempt 1</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="answered2">
                <label class="form-check-label" for="answered2">Attempt 2</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="answered3">
                <label class="form-check-label" for="answered3">Attempt 3</label>
              </div>

              <hr>

              <!-- Not Answered Call Section -->
              <h6 class="text-danger"><i class="fas fa-times-circle me-2"></i>Not Answered Call</h6>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="notanswered1">
                <label class="form-check-label" for="notanswered1">Attempt 1</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="notanswered2">
                <label class="form-check-label" for="notanswered2">Attempt 2</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="notanswered3">
                <label class="form-check-label" for="notanswered3">Attempt 3</label>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-success" onclick="saveCallStatus()">Save</button>
            </div>

          </div>
        </div>
      </div>


      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="js/script.js"></script>

      <script>
        document.addEventListener("DOMContentLoaded", function() {
          const nicInput = document.querySelector("input[name='nic']");
          const contactInput = document.querySelector("input[name='contact']");

          // ✅ NIC Validation: only up to 12 characters (letters + numbers allowed)
          nicInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9a-zA-Z]/g, ""); // only letters + numbers
            if (this.value.length > 12) {
              this.value = this.value.slice(0, 12);
            }

            if (this.value.length !== 12) {
              this.setCustomValidity("NIC must be exactly 12 characters (letters/numbers).");
            } else {
              this.setCustomValidity("");
            }
          });

          // ✅ Contact Validation: must start with 0 and be exactly 10 digits
          contactInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9]/g, ""); // only numbers
            if (this.value.length > 10) {
              this.value = this.value.slice(0, 10); // force max 10 digits
            }

            if (!/^0\d{9}$/.test(this.value)) {
              this.setCustomValidity("Contact number must be 10 digits and start with 0.");
            } else {
              this.setCustomValidity("");
            }
          });
        });
      </script>

  </body>

  </html>
<?php
} else {
  header("Location: login.php");
}
?>