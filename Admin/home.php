<?php
 require_once "../auth_check.php"
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Aushvera Admin</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <style>
      :root {
        --primary: #1a2a4a;
        --secondary: #d4af37;
        --light-bg: #f5f5f5;
        --dark-text: #333333;
        --white: #ffffff;
        --gray: #e0e0e0;
        --danger: #e74c3c;
        --success: #2ecc71;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
      }

      body {
        display: flex;
        background-color: var(--light-bg);
        color: var(--dark-text);
      }

      /* Sidebar Styles */
      .sidebar {
        width: 250px;
        height: 100vh;
        background-color: var(--primary);
        color: var(--white);
        padding: 20px 0;
        position: fixed;
        transition: all 0.3s;
      }

      .sidebar-header {
        padding: 0 20px 30px;
        display: flex;
        align-items: center;
      }

      .sidebar-header h2 {
        margin-left: 10px;
        font-weight: 600;
      }

      .sidebar-menu {
        list-style: none;
      }

      .sidebar-menu li {
        margin-bottom: 5px;
      }

      .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: var(--white);
        text-decoration: none;
        transition: all 0.3s;
      }

      .sidebar-menu a:hover,
      .sidebar-menu a.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-left: 4px solid var(--secondary);
      }

      .sidebar-menu i {
        margin-right: 10px;
        font-size: 18px;
      }

      /* Main Content Styles */
      .main-content {
        margin-left: 250px;
        width: calc(100% - 250px);
        padding: 20px;
        transition: all 0.3s;
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }

      .header h1 {
        font-size: 24px;
        font-weight: 600;
      }

      .search-bar {
        display: flex;
        align-items: center;
      }

      .search-bar input {
        padding: 10px 15px;
        border: 1px solid var(--gray);
        border-radius: 4px;
        width: 250px;
        margin-right: 10px;
      }

      .search-bar select {
        padding: 10px;
        border: 1px solid var(--gray);
        border-radius: 4px;
        background-color: var(--white);
      }

      .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
      }

      .btn-primary {
        background-color: var(--secondary);
        color: var(--primary);
      }

      .btn-primary:hover {
        background-color: #c9a227;
      }

      /* Summary Cards */
      .summary-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
      }

.card {
      background: white;
      padding: 20px;
      flex: 1;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .products-table {
      margin-top: 30px;
      background: white;
      padding: 20px;
      border-radius: 6px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f5f5f5;
    }
    .btn-primary {
      background-color: var(--secondary);
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .hidden {
      display: none;
    }
      .card {
        background-color: var(--white);
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      }

      .card h3 {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
      }

      .card p {
        font-size: 24px;
        font-weight: 600;
      }

      /* Products Table */
      .products-table {
        background-color: var(--white);
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      th,
      td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid var(--gray);
      }

      th {
        background-color: #f9f9f9;
        font-weight: 600;
      }

      .product-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
      }

      .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
      }

      .status-active {
        background-color: rgba(46, 204, 113, 0.2);
        color: var(--success);
      }

      .status-out-of-stock {
        background-color: rgba(231, 76, 60, 0.2);
        color: var(--danger);
      }

      .action-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 5px;
        transition: all 0.3s;
      }

      .edit-btn {
        background-color: rgba(26, 42, 74, 0.1);
        color: var(--primary);
      }

      .edit-btn:hover {
        background-color: rgba(26, 42, 74, 0.2);
      }

      .delete-btn {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--danger);
      }

      .delete-btn:hover {
        background-color: rgba(231, 76, 60, 0.2);
      }

      /* Modal Styles */
      .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
      }

      .modal-content {
        background-color: var(--white);
        border-radius: 8px;
        width: 80%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      }

      .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--gray);
      }

      .modal-header h2 {
        font-size: 20px;
        color: var(--primary);
      }

      .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
      }

      .form-group {
        margin-bottom: 20px;
      }

      .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
      }

      .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--gray);
        border-radius: 4px;
        font-size: 14px;
      }

      .form-row {
        display: flex;
        gap: 20px;
      }

      .form-col {
        flex: 1;
      }

      .image-upload {
        border: 2px dashed var(--gray);
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        margin-bottom: 15px;
      }

      .image-upload:hover {
        border-color: var(--secondary);
      }

      .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
      }

      .preview-item {
        position: relative;
        width: 80px;
        height: 80px;
      }

      .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
      }

      .preview-item .remove-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: var(--danger);
        color: white;
        border: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 10px;
      }

      .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--gray);
      }

      .btn-secondary {
        background-color: var(--gray);
        color: var(--dark-text);
      }

      .btn-secondary:hover {
        background-color: #d0d0d0;
      }

      /* Responsive Styles */
      @media (max-width: 992px) {
        .sidebar {
          width: 80px;
          overflow: hidden;
        }

        .sidebar-header h2,
        .sidebar-menu a span {
          display: none;
        }

        .sidebar-menu a {
          justify-content: center;
          padding: 12px 0;
        }

        .sidebar-menu i {
          margin-right: 0;
          font-size: 20px;
        }

        .main-content {
          margin-left: 80px;
          width: calc(100% - 80px);
        }
      }

      @media (max-width: 768px) {
        .summary-cards {
          grid-template-columns: 1fr;
        }

        .form-row {
          flex-direction: column;
          gap: 0;
        }

        .modal-content {
          width: 95%;
          padding: 20px;
        }
      }

      .existing-images {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
      }

      .existing-image-item {
        position: relative;
        width: 80px;
        height: 80px;
      }

      .existing-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
      }

      .existing-image-item .remove-btn {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: var(--danger);
        color: white;
        border: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 10px;
      }
    </style>
  </head>
  <body>
    <!-- Sidebar -->
 
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-store" style="font-size: 24px; color: var(--secondary)"></i>
      <h2>Aushvera</h2>
    </div>
    <ul class="sidebar-menu">
      <li><a href="#"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
      <li><a href="#" class="active"><i class="fas fa-box-open"></i> <span>Products</span></a></li>
      <li><a href="#"><i class="fas fa-shopping-bag"></i> <span>Orders</span></a></li>
      <li><a href="#"><i class="fas fa-users"></i> <span>Customers</span></a></li>
      <li><a href="#"><i class="fas fa-comment-alt"></i> <span>Feedback</span></a></li>
      <li><a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header">
      <h1>Product Management</h1>
      <div class="search-bar">
        <input type="text" placeholder="Search products..." />
        <select>
          <option>All Categories</option>
          <option>Skincare</option>
          <option>Haircare</option>
          <option>Makeup</option>
        </select>
      </div>
      <button class="btn-primary" id="addProductBtn">
        <i class="fas fa-plus"></i> Add New Product
      </button>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
      <div class="card">
        <h3>Total Products</h3>
        <p id="totalProducts">24</p>
      </div>
      <div class="card">
        <h3>Active Products</h3>
        <p id="activeProducts">22</p>
      </div>
      <div class="card">
        <h3>Out of Stock</h3>
        <p id="outOfStock">2</p>
      </div>
    </div>

    <!-- Products Table -->
    <div class="products-table" id="products-section">
      <table>
        <thead>
          <tr>
            <th>Thumbnail</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="productsTableBody">
          <!-- Products will be added here dynamically -->
        </tbody>
      </table>
    </div>

    <!-- Customers Section -->
    <div class="products-table" id="customer-section" style="display: none;">
      <h2>Customers</h2>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date of Birth</th>
            <th>Address</th>
          </tr>
        </thead>
        <tbody id="customersTableBody"></tbody>
      </table>
    </div>

    <!-- Orders Section -->
    <div class="products-table" id="orders-section" style="display: none;">
      <h2>Orders</h2>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Items</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Ordered Date</th>
          </tr>
        </thead>
        <tbody id="ordersTableBody"></tbody>
      </table>
    </div>

    <!-- Feedback Section -->
    <div class="products-table" id="feedback-section" style="display: none;">
      <h2>User Feedback</h2>
      <table>
        <thead>
          <tr>
            <th>Feedback ID</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Rating</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody id="feedbackTableBody"></tbody>
      </table>
    </div>
  </div>

  <!-- Script Section -->
  <script>
    function loadOrders() {
      fetch("php/fetch_orders.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("ordersTableBody");
          tbody.innerHTML = "";
          if (data.success) {
            data.orders.forEach(order => {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td>${order.order_id}</td>
                <td>${order.customer_name}</td>
                <td>${order.customer_email}</td>
                <td>${order.customer_phone}</td>
                <td>${order.product_name}</td>
                <td>₹${parseFloat(order.total_amount).toFixed(2)}</td>
                <td>${order.status}</td>
                <td>${new Date(order.ordered_date).toLocaleString()}</td>
              `;
              tbody.appendChild(row);
            });
          }
        });
    }

    function loadFeedback() {
      fetch("php/fetch_feedback.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("feedbackTableBody");
          tbody.innerHTML = "";
          if (data.success) {
            data.feedback.forEach(fb => {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td>${fb.id}</td>
                <td>${fb.name}</td>
                <td>${fb.email}</td>
                <td>${fb.message}</td>
                <td>${'⭐'.repeat(fb.rating)}</td>
                <td>${new Date(fb.date).toLocaleString()}</td>
              `;
              tbody.appendChild(row);
            });
          }
        });
    }

    function loadCustomers() {
      fetch("php/fetch_customers.php")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("customersTableBody");
          tbody.innerHTML = "";
          if (data.success) {
            data.customers.forEach(cust => {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td>${cust.name}</td>
                <td>${cust.email}</td>
                <td>${cust.phone}</td>
                <td>${cust.date_of_birth}</td>
                <td>${cust.city}</td>
              `;
              tbody.appendChild(row);
            });
          }
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
      const sidebarLinks = document.querySelectorAll(".sidebar-menu a");
      const productsSection = document.getElementById("products-section");
      const customerSection = document.getElementById("customer-section");
      const ordersSection = document.getElementById("orders-section");
      const feedbackSection = document.getElementById("feedback-section");

      sidebarLinks.forEach(link => {
        link.addEventListener("click", function (e) {
          e.preventDefault();
          const text = this.textContent.trim().toLowerCase();

          productsSection.style.display = "none";
          customerSection.style.display = "none";
          ordersSection.style.display = "none";
          feedbackSection.style.display = "none";

          if (text === "products") {
            productsSection.style.display = "block";
          } else if (text === "customers") {
            customerSection.style.display = "block";
            loadCustomers();
          } else if (text === "orders") {
            ordersSection.style.display = "block";
            loadOrders();
          } else if (text === "feedback") {
            feedbackSection.style.display = "block";
            loadFeedback();
          }

          sidebarLinks.forEach(l => l.classList.remove("active"));
          this.classList.add("active");
        });
      });
    });
  </script>

    <!-- Add Product Modal -->
    <div class="modal" id="productModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 id="modalTitle">Add New Product</h2>
          <button class="close-btn" id="closeModalBtn">&times;</button>
        </div>
        <form
          id="productForm"
          action="php/product.php"
          method="POST"
          enctype="multipart/form-data"
        >
          <input type="hidden" id="productId" name="product_id" />
          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label for="productName">Product Name *</label>
                <input
                  type="text"
                  id="productName"
                  name="productName"
                  class="form-control"
                  required
                />
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label for="productCategory">Category *</label>
                <select
                  id="productCategory"
                  name="productCategory"
                  class="form-control"
                  required
                >
                  <option value="">Select Category</option>
                  <option value="Beverages">Beverages</option>
                  <option value="Haircare">Haircare</option>
                  <option value="Makeup">Makeup</option>
                  <option value="Fragrance">Fragrance</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="productDescription">Description</label>
            <textarea
              id="productDescription"
              name="productDescription"
              class="form-control"
              rows="4"
            ></textarea>
          </div>

          <div class="form-group">
            <label>Product Images</label>
            <div
              class="image-upload"
              id="imageUpload"
              onclick="document.getElementById('fileInput').click();"
            >
              <i
                class="fas fa-cloud-upload-alt"
                style="font-size: 24px; color: var(--secondary)"
              ></i>
              <p>Drag & drop images here or click to browse</p>
              <input
                type="file"
                name="productImages[]"
                id="fileInput"
                accept="image/*"
                multiple
                style="display: none"
              />
            </div>
            <div class="image-preview" id="imagePreview"></div>
            <div id="existingImages" class="existing-images"></div>
          </div>

          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label for="productPrice">Price *</label>
                <input
                  type="number"
                  id="productPrice"
                  name="productPrice"
                  class="form-control"
                  min="0"
                  step="0.01"
                  required
                />
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label for="productDiscount">Discount Price</label>
                <input
                  type="number"
                  id="productDiscount"
                  name="productDiscount"
                  class="form-control"
                  min="0"
                  step="0.01"
                />
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label for="productSKU">SKU *</label>
                <input
                  type="text"
                  id="productSKU"
                  name="productSKU"
                  class="form-control"
                  required
                />
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label for="productStock">Stock Quantity *</label>
                <input
                  type="number"
                  id="productStock"
                  name="productStock"
                  class="form-control"
                  min="0"
                  required
                />
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label for="productWeight">Weight (in grams)</label>
                <input
                  type="number"
                  id="productWeight"
                  name="productWeight"
                  class="form-control"
                  min="0"
                  step="0.1"
                  placeholder="e.g., 50"
                />
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label for="productStatus">Status</label>
                <select
                  id="productStatus"
                  name="productStatus"
                  class="form-control"
                >
                  <option value="active">Active</option>
                  <option value="draft">Draft</option>
                </select>
              </div>
            </div>
          </div>


          <div class="form-group">
            <label>Product Detail Page</label>
            <div id="productDetailsContainer">
              <div class="detail-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                <input type="text" name="productDetails[]" class="form-control" placeholder="Enter product detail" style="margin-right: 10px;">
                <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addDetailField()"><i class="fas fa-plus"></i></button>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Ingredients</label>
            <input type="text" name="ingredientsHeading" class="form-control" placeholder="Enter ingredients section heading" style="margin-bottom: 15px;">
            <div id="ingredientsContainer">
              <div class="ingredient-item" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                <input type="text" name="ingredients[]" class="form-control" placeholder="Ingredient" style="flex: 1;">
                <input type="text" name="ayurvedicNames[]" class="form-control" placeholder="Ayurvedic Name" style="flex: 1;">
                <input type="text" name="keyBenefits[]" class="form-control" placeholder="Key Benefits" style="flex: 1;">
                <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addIngredientField()"><i class="fas fa-plus"></i></button>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>How to Use</label>
            <input type="text" name="howToUseHeading" class="form-control" placeholder="Enter how to use section heading" style="margin-bottom: 15px;">
            <div id="howToUseContainer">
              <div class="howto-item" style="display: flex; align-items: center; margin-bottom: 10px;">
                <input type="text" name="howToUse[]" class="form-control" placeholder="Enter usage instruction" style="margin-right: 10px;">
                <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addHowToUseField()"><i class="fas fa-plus"></i></button>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelBtn">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
              Save Product
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const addProductBtn = document.getElementById("addProductBtn");
        const productModal = document.getElementById("productModal");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const cancelBtn = document.getElementById("cancelBtn");
        const fileInput = document.getElementById("fileInput");
        const imageUpload = document.getElementById("imageUpload");
        const imagePreview = document.getElementById("imagePreview");
        const searchInput = document.querySelector(".search-bar input");
        const categoryFilter = document.querySelector(".search-bar select");
        const modalTitle = document.getElementById("modalTitle");
        const submitBtn = document.getElementById("submitBtn");
        const productForm = document.getElementById("productForm");

        let allProducts = []; // Store all products for filtering
        let isEditMode = false;

        // Load products on page load
        loadProducts();

        // Function to load products from database
        function loadProducts() {
          fetch("php/fetch_all_products.php")
            .then((response) => response.json())
            .then((data) => {
              if (data.error) {
                console.error("Error:", data.error);
                return;
              }

              allProducts = data.products; // Store all products

              // Update summary cards
              document.getElementById("totalProducts").textContent =
                data.statistics.total;
              document.getElementById("activeProducts").textContent =
                data.statistics.active;
              document.getElementById("outOfStock").textContent =
                data.statistics.outOfStock;

              // Display products
              displayProducts(allProducts);
            })
            .catch((error) => {
              console.error("Error fetching products:", error);
            });
        }

        // Function to display products
        function displayProducts(products) {
          const tableBody = document.getElementById("productsTableBody");
          tableBody.innerHTML = "";

          products.forEach((product) => {
            const row = document.createElement("tr");
            row.innerHTML = `
            <td>
              <img src="../user/php/uploads/${product.image}" alt="${
              product.name
            }" class="product-thumbnail" onerror="this.src='../user/Images/tea1.png'">
            </td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td>₹${product.price}</td>
            <td>${product.stock}</td>
            <td>
              <span class="status ${
                product.status === "active"
                  ? "status-active"
                  : "status-out-of-stock"
              }">
                ${product.status === "active" ? "Active" : "Inactive"}
              </span>
            </td>
            <td>
              <button class="action-btn edit-btn" onclick="editProduct(${
                product.id
              })">
                <i class="fas fa-edit"></i>
              </button>
              <button class="action-btn delete-btn" onclick="deleteProduct(${
                product.id
              })">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          `;
            tableBody.appendChild(row);
          });
        }

        // Search functionality
        searchInput.addEventListener("input", function () {
          filterProducts();
        });

        // Category filter functionality
        categoryFilter.addEventListener("change", function () {
          filterProducts();
        });

        // Function to filter products
        function filterProducts() {
          const searchTerm = searchInput.value.toLowerCase();
          const selectedCategory = categoryFilter.value;

          let filteredProducts = allProducts.filter((product) => {
            const matchesSearch =
              product.name.toLowerCase().includes(searchTerm) ||
              product.description.toLowerCase().includes(searchTerm) ||
              product.sku.toLowerCase().includes(searchTerm);
            const matchesCategory =
              selectedCategory === "" || product.category === selectedCategory;

            return matchesSearch && matchesCategory;
          });

          displayProducts(filteredProducts);
        }

        // Open modal for adding new product
        addProductBtn.addEventListener("click", () => {
          isEditMode = false;
          modalTitle.textContent = "Add New Product";
          submitBtn.textContent = "Save Product";
          productForm.action = "php/product.php";
          productForm.reset();
          imagePreview.innerHTML = "";
          document.getElementById("existingImages").innerHTML = "";
          document.getElementById("productId").value = "";
          
          // Reset dynamic fields
          const productDetailsContainer = document.getElementById('productDetailsContainer');
          productDetailsContainer.innerHTML = `
            <div class="detail-item" style="display: flex; align-items: center; margin-bottom: 10px;">
              <input type="text" name="productDetails[]" class="form-control" placeholder="Enter product detail" style="margin-right: 10px;">
              <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addDetailField()"><i class="fas fa-plus"></i></button>
            </div>
          `;
          
          const ingredientsContainer = document.getElementById('ingredientsContainer');
          ingredientsContainer.innerHTML = `
            <div class="ingredient-item" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
              <input type="text" name="ingredients[]" class="form-control" placeholder="Ingredient" style="flex: 1;">
              <input type="text" name="ayurvedicNames[]" class="form-control" placeholder="Ayurvedic Name" style="flex: 1;">
              <input type="text" name="keyBenefits[]" class="form-control" placeholder="Key Benefits" style="flex: 1;">
              <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addIngredientField()"><i class="fas fa-plus"></i></button>
            </div>
          `;
          
          const howToUseContainer = document.getElementById('howToUseContainer');
          howToUseContainer.innerHTML = `
            <div class="howto-item" style="display: flex; align-items: center; margin-bottom: 10px;">
              <input type="text" name="howToUse[]" class="form-control" placeholder="Enter usage instruction" style="margin-right: 10px;">
              <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addHowToUseField()"><i class="fas fa-plus"></i></button>
            </div>
          `;
          
          // Clear heading fields
          const ingredientsHeadingInput = document.querySelector('input[name="ingredientsHeading"]');
          if (ingredientsHeadingInput) {
            ingredientsHeadingInput.value = '';
          }
          
          const howToUseHeadingInput = document.querySelector('input[name="howToUseHeading"]');
          if (howToUseHeadingInput) {
            howToUseHeadingInput.value = '';
          }
          
          productModal.style.display = "flex";
          document.body.style.overflow = "hidden";
        });

        // Close modal
        function closeModal() {
          productModal.style.display = "none";
          document.body.style.overflow = "auto";
          productForm.reset();
          imagePreview.innerHTML = "";
          document.getElementById("existingImages").innerHTML = "";
          
          // Reset dynamic fields
          const productDetailsContainer = document.getElementById('productDetailsContainer');
          productDetailsContainer.innerHTML = `
            <div class="detail-item" style="display: flex; align-items: center; margin-bottom: 10px;">
              <input type="text" name="productDetails[]" class="form-control" placeholder="Enter product detail" style="margin-right: 10px;">
              <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addDetailField()"><i class="fas fa-plus"></i></button>
            </div>
          `;
          
          const ingredientsContainer = document.getElementById('ingredientsContainer');
          ingredientsContainer.innerHTML = `
            <div class="ingredient-item" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
              <input type="text" name="ingredients[]" class="form-control" placeholder="Ingredient" style="flex: 1;">
              <input type="text" name="ayurvedicNames[]" class="form-control" placeholder="Ayurvedic Name" style="flex: 1;">
              <input type="text" name="keyBenefits[]" class="form-control" placeholder="Key Benefits" style="flex: 1;">
              <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addIngredientField()"><i class="fas fa-plus"></i></button>
            </div>
          `;
          
          const howToUseContainer = document.getElementById('howToUseContainer');
          howToUseContainer.innerHTML = `
            <div class="howto-item" style="display: flex; align-items: center; margin-bottom: 10px;">
              <input type="text" name="howToUse[]" class="form-control" placeholder="Enter usage instruction" style="margin-right: 10px;">
              <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addHowToUseField()"><i class="fas fa-plus"></i></button>
            </div>
          `;
          
          // Clear heading fields
          const ingredientsHeadingInput = document.querySelector('input[name="ingredientsHeading"]');
          if (ingredientsHeadingInput) {
            ingredientsHeadingInput.value = '';
          }
          
          const howToUseHeadingInput = document.querySelector('input[name="howToUseHeading"]');
          if (howToUseHeadingInput) {
            howToUseHeadingInput.value = '';
          }
          
          isEditMode = false;
        }

        closeModalBtn.addEventListener("click", closeModal);
        cancelBtn.addEventListener("click", closeModal);

        // Click outside modal to close
        window.addEventListener("click", (e) => {
          if (e.target === productModal) {
            closeModal();
          }
        });

        // Handle form submission
        productForm.addEventListener("submit", function (e) {
          e.preventDefault();

          const formData = new FormData(this);
          const action = isEditMode
            ? "php/update_product.php"
            : "php/product.php";

          fetch(action, {
            method: "POST",
            body: formData,
          })
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                alert(data.message);
                closeModal();
                loadProducts(); // Reload products after adding/updating
              } else {
                alert("Error: " + data.message);
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              alert("Error saving product. Please try again.");
            });
        });

        // Image preview handler
        fileInput.addEventListener("change", () => {
          imagePreview.innerHTML = ""; // Clear previous previews
          Array.from(fileInput.files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = (e) => {
              const img = document.createElement("img");
              img.src = e.target.result;
              img.style.width = "80px";
              img.style.height = "80px";
              img.style.objectFit = "cover";
              img.style.borderRadius = "4px";
              img.style.marginRight = "10px";
              imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
          });
        });

        // Function to edit product
        window.editProduct = function (productId) {
          fetch(`php/get_product.php?id=${productId}`)
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                const product = data.product;

                // Set form values
                document.getElementById("productId").value = product.id;
                document.getElementById("productName").value = product.name;
                document.getElementById("productCategory").value =
                  product.category;
                document.getElementById("productDescription").value =
                  product.description;
                document.getElementById("productPrice").value = product.price;
                document.getElementById("productDiscount").value =
                  product.discount || "";
                document.getElementById("productSKU").value = product.sku;
                document.getElementById("productStock").value = product.stock;
                document.getElementById("productWeight").value = product.weight || "";
                document.getElementById("productStatus").value = product.status;

                // Populate product details
                const productDetailsContainer = document.getElementById('productDetailsContainer');
                productDetailsContainer.innerHTML = '';
                
                if (product.productDetailsArray && product.productDetailsArray.length > 0) {
                  product.productDetailsArray.forEach((detail, index) => {
                    const detailItem = document.createElement('div');
                    detailItem.className = 'detail-item';
                    detailItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 10px;';
                    
                    const buttonClass = index === 0 ? 'btn' : 'btn';
                    const buttonStyle = index === 0 ? 
                      'background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;' :
                      'background-color: var(--danger); color: white; padding: 8px 12px; border-radius: 4px;';
                    const buttonIcon = index === 0 ? 'fas fa-plus' : 'fas fa-minus';
                    const buttonOnclick = index === 0 ? 'addDetailField()' : 'removeDetailField(this)';
                    
                    detailItem.innerHTML = `
                      <input type="text" name="productDetails[]" class="form-control" value="${detail}" placeholder="Enter product detail" style="margin-right: 10px;">
                      <button type="button" class="${buttonClass}" style="${buttonStyle}" onclick="${buttonOnclick}"><i class="${buttonIcon}"></i></button>
                    `;
                    productDetailsContainer.appendChild(detailItem);
                  });
                } else {
                  // Add default empty field
                  const detailItem = document.createElement('div');
                  detailItem.className = 'detail-item';
                  detailItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 10px;';
                  detailItem.innerHTML = `
                    <input type="text" name="productDetails[]" class="form-control" placeholder="Enter product detail" style="margin-right: 10px;">
                    <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addDetailField()"><i class="fas fa-plus"></i></button>
                  `;
                  productDetailsContainer.appendChild(detailItem);
                }

                // Populate ingredients heading
                const ingredientsHeadingInput = document.querySelector('input[name="ingredientsHeading"]');
                if (ingredientsHeadingInput) {
                  ingredientsHeadingInput.value = product.ingredients_heading || '';
                }

                // Populate ingredients data
                const ingredientsContainer = document.getElementById('ingredientsContainer');
                ingredientsContainer.innerHTML = '';
                
                if (product.ingredientsArray && product.ingredientsArray.length > 0) {
                  product.ingredientsArray.forEach((ingredient, index) => {
                    const ingredientItem = document.createElement('div');
                    ingredientItem.className = 'ingredient-item';
                    ingredientItem.style.cssText = 'display: flex; gap: 10px; align-items: center; margin-bottom: 10px;';
                    
                    const buttonClass = index === 0 ? 'btn' : 'btn';
                    const buttonStyle = index === 0 ? 
                      'background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;' :
                      'background-color: var(--danger); color: white; padding: 8px 12px; border-radius: 4px;';
                    const buttonIcon = index === 0 ? 'fas fa-plus' : 'fas fa-minus';
                    const buttonOnclick = index === 0 ? 'addIngredientField()' : 'removeIngredientField(this)';
                    
                    ingredientItem.innerHTML = `
                      <input type="text" name="ingredients[]" class="form-control" value="${ingredient.ingredient || ''}" placeholder="Ingredient" style="flex: 1;">
                      <input type="text" name="ayurvedicNames[]" class="form-control" value="${ingredient.ayurvedicName || ''}" placeholder="Ayurvedic Name" style="flex: 1;">
                      <input type="text" name="keyBenefits[]" class="form-control" value="${ingredient.keyBenefits || ''}" placeholder="Key Benefits" style="flex: 1;">
                      <button type="button" class="${buttonClass}" style="${buttonStyle}" onclick="${buttonOnclick}"><i class="${buttonIcon}"></i></button>
                    `;
                    ingredientsContainer.appendChild(ingredientItem);
                  });
                } else {
                  // Add default empty field
                  const ingredientItem = document.createElement('div');
                  ingredientItem.className = 'ingredient-item';
                  ingredientItem.style.cssText = 'display: flex; gap: 10px; align-items: center; margin-bottom: 10px;';
                  ingredientItem.innerHTML = `
                    <input type="text" name="ingredients[]" class="form-control" placeholder="Ingredient" style="flex: 1;">
                    <input type="text" name="ayurvedicNames[]" class="form-control" placeholder="Ayurvedic Name" style="flex: 1;">
                    <input type="text" name="keyBenefits[]" class="form-control" placeholder="Key Benefits" style="flex: 1;">
                    <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addIngredientField()"><i class="fas fa-plus"></i></button>
                  `;
                  ingredientsContainer.appendChild(ingredientItem);
                }

                // Populate how to use heading
                const howToUseHeadingInput = document.querySelector('input[name="howToUseHeading"]');
                if (howToUseHeadingInput) {
                  howToUseHeadingInput.value = product.how_to_use_heading || '';
                }

                // Populate how to use data
                const howToUseContainer = document.getElementById('howToUseContainer');
                howToUseContainer.innerHTML = '';
                
                if (product.howToUseArray && product.howToUseArray.length > 0) {
                  product.howToUseArray.forEach((howToUse, index) => {
                    const howToItem = document.createElement('div');
                    howToItem.className = 'howto-item';
                    howToItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 10px;';
                    
                    const buttonClass = index === 0 ? 'btn' : 'btn';
                    const buttonStyle = index === 0 ? 
                      'background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;' :
                      'background-color: var(--danger); color: white; padding: 8px 12px; border-radius: 4px;';
                    const buttonIcon = index === 0 ? 'fas fa-plus' : 'fas fa-minus';
                    const buttonOnclick = index === 0 ? 'addHowToUseField()' : 'removeHowToUseField(this)';
                    
                    howToItem.innerHTML = `
                      <input type="text" name="howToUse[]" class="form-control" value="${howToUse}" placeholder="Enter usage instruction" style="margin-right: 10px;">
                      <button type="button" class="${buttonClass}" style="${buttonStyle}" onclick="${buttonOnclick}"><i class="${buttonIcon}"></i></button>
                    `;
                    howToUseContainer.appendChild(howToItem);
                  });
                } else {
                  // Add default empty field
                  const howToItem = document.createElement('div');
                  howToItem.className = 'howto-item';
                  howToItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 10px;';
                  howToItem.innerHTML = `
                    <input type="text" name="howToUse[]" class="form-control" placeholder="Enter usage instruction" style="margin-right: 10px;">
                    <button type="button" class="btn" style="background-color: var(--secondary); color: var(--primary); padding: 8px 12px; border-radius: 4px;" onclick="addHowToUseField()"><i class="fas fa-plus"></i></button>
                  `;
                  howToUseContainer.appendChild(howToItem);
                }

                // Display existing images
                const existingImagesDiv =
                  document.getElementById("existingImages");
                existingImagesDiv.innerHTML = "";

                if (product.imageArray && product.imageArray.length > 0) {
                  product.imageArray.forEach((image) => {
                    if (image.trim()) {
                      const imageItem = document.createElement("div");
                      imageItem.className = "existing-image-item";
                      imageItem.innerHTML = `
                      <img src="../user/php/uploads/${image.trim()}" alt="Existing image" onerror="this.style.display='none'">
                      <button type="button" class="remove-btn" onclick="removeExistingImage(this)">&times;</button>
                    `;
                      existingImagesDiv.appendChild(imageItem);
                    }
                  });
                }

                // Update modal for edit mode
                document.getElementById("modalTitle").textContent =
                  "Edit Product";
                document.getElementById("submitBtn").textContent =
                  "Update Product";
                document.getElementById("productForm").action =
                  "php/update_product.php";

                // Show modal
                document.getElementById("productModal").style.display = "flex";
                document.body.style.overflow = "hidden";

                // Set edit mode
                isEditMode = true;
              } else {
                alert("Error: " + data.message);
              }
            })
            .catch((error) => {
              console.error("Error:", error);
              alert("Error loading product details. Please try again.");
            });
        };

        // Function to delete product
        window.deleteProduct = function(productId) {
          if (
            confirm(
              "Are you sure you want to delete this product? This action cannot be undone."
            )
          ) {
            // Show loading state
            const deleteBtn = event.target.closest('.delete-btn');
            const originalContent = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            deleteBtn.disabled = true;

            const formData = new FormData();
            formData.append("product_id", productId);

            fetch("php/delete_product.php", {
              method: "POST",
              body: formData,
            })
              .then((response) => {
                if (!response.ok) {
                  throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
              })
              .then((data) => {
                if (data.success) {
                  alert(data.message);
                  // Reload products instead of full page reload
                  loadProducts();
                } else {
                  alert("Error: " + data.message);
                }
              })
              .catch((error) => {
                console.error("Error:", error);
                alert("Error deleting product. Please try again. Error: " + error.message);
              })
              .finally(() => {
                // Restore button state
                deleteBtn.innerHTML = originalContent;
                deleteBtn.disabled = false;
              });
          }
        };

        // Function to remove existing image
        function removeExistingImage(button) {
          button.parentElement.remove();
        }

        // Make functions globally accessible
        window.addDetailField = function() {
          const container = document.getElementById('productDetailsContainer');
          const newDetailItem = document.createElement('div');
          newDetailItem.className = 'detail-item';
          newDetailItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 10px;';
          newDetailItem.innerHTML = `
            <input type="text" name="productDetails[]" class="form-control" placeholder="Enter product detail" style="margin-right: 10px;">
            <button type="button" class="btn" style="background-color: var(--danger); color: white; padding: 8px 12px; border-radius: 4px;" onclick="removeDetailField(this)"><i class="fas fa-minus"></i></button>
          `;
          container.appendChild(newDetailItem);
        };

        window.removeDetailField = function(button) {
          button.parentElement.remove();
        };

        // Function to add new ingredient field
        window.addIngredientField = function() {
          const container = document.getElementById('ingredientsContainer');
          const newIngredientItem = document.createElement('div');
          newIngredientItem.className = 'ingredient-item';
          newIngredientItem.style.cssText = 'display: flex; gap: 10px; align-items: center; margin-bottom: 10px;';
          newIngredientItem.innerHTML = `
            <input type="text" name="ingredients[]" class="form-control" placeholder="Ingredient" style="flex: 1;">
            <input type="text" name="ayurvedicNames[]" class="form-control" placeholder="Ayurvedic Name" style="flex: 1;">
            <input type="text" name="keyBenefits[]" class="form-control" placeholder="Key Benefits" style="flex: 1;">
            <button type="button" class="btn" style="background-color: var(--danger); color: white; padding: 8px 12px; border-radius: 4px;" onclick="removeIngredientField(this)"><i class="fas fa-minus"></i></button>
          `;
          container.appendChild(newIngredientItem);
        };

        // Function to remove ingredient field
        window.removeIngredientField = function(button) {
          button.parentElement.remove();
        };

        // Function to add new how to use field
        window.addHowToUseField = function() {
          const container = document.getElementById('howToUseContainer');
          const newHowToItem = document.createElement('div');
          newHowToItem.className = 'howto-item';
          newHowToItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 10px;';
          newHowToItem.innerHTML = `
            <input type="text" name="howToUse[]" class="form-control" placeholder="Enter usage instruction" style="margin-right: 10px;">
            <button type="button" class="btn" style="background-color: var(--danger); color: white; padding: 8px 12px; border-radius: 4px;" onclick="removeHowToUseField(this)"><i class="fas fa-minus"></i></button>
          `;
          container.appendChild(newHowToItem);
        };

        // Function to remove how to use field
        window.removeHowToUseField = function(button) {
          button.parentElement.remove();
        };

        // Fetch and display customers
        function loadCustomers() {
          fetch("php/fetch_customers.php")
            .then((response) => response.json())
            .then((data) => {
              if (data.success) {
                const tbody = document.getElementById("customersTableBody");
                tbody.innerHTML = "";
                data.customers.forEach((cust) => {
                  const row = document.createElement("tr");
                  row.innerHTML = `
                        <td>${cust.name || ""}</td>
                        <td>${cust.email || ""}</td>
                        <td>${cust.phone || ""}</td>
                        <td>${cust.date_of_birth || ""}</td>
                        <td>${cust.city || ""}</td>
                    `;
                  tbody.appendChild(row);
                });
              } else {
                alert("Failed to load customers: " + data.message);
              }
            })
            .catch((err) => {
              console.error("Error loading customers:", err);
            });
        }

        // Sidebar navigation logic
        const sidebarLinks = document.querySelectorAll(".sidebar-menu a");
        const productsSection = document.getElementById("products-section");
        const customerSection = document.getElementById("customer-section");

        sidebarLinks.forEach((link) => {
          link.addEventListener("click", function (e) {
            const text = this.textContent.trim().toLowerCase();
            if (text === "customers") {
              e.preventDefault();
              productsSection.style.display = "none";
              customerSection.style.display = "block";
              loadCustomers();
              // Set active class
              sidebarLinks.forEach((l) => l.classList.remove("active"));
              this.classList.add("active");
            } else if (text === "products") {
              e.preventDefault();
              productsSection.style.display = "block";
              customerSection.style.display = "none";
              // Set active class
              sidebarLinks.forEach((l) => l.classList.remove("active"));
              this.classList.add("active");
            }
          });
        });
      });
    </script>
  </body>
</html>
