<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addresses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>

        /* Address blocks */

        .btn-primary {
            background-color: #000;
            border-color: #000;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #333;
            border-color: #333;
            color: #fff;
        }

        .btn-danger {
            background-color: #000;
            border-color: #000;
        }

        .btn-danger:hover {
            background-color: #990000;
            border-color: #990000;
        }

        .btn-secondary {
            background-color: #000;
            border-color: #000;
        }

        .btn-secondary:hover {
            background-color: #666;
            border-color: #666;
        }

        .modal-content {
            background-color: #fff;
        }

        .modal-content .btn-primary {
            background-color: #000;
            border-color: #000;
        }

        .modal-content .btn-primary:hover {
            background-color: #333;
            border-color: #333;
        }

        /* Additional customizations */
        .form-control {
            background-color: #fff;
            color: #000;
            border-color: #ccc;
        }

        .form-check-input:checked {
            background-color: #000;
        }

        .form-check-input:checked ~ .form-check-label {
            color: #000;
        }

        .form-check-input:checked:hover {
            background-color: #333;
        }

        .address-container {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .btn-primary, .btn-danger, .btn-secondary {
            margin-right: 10px;
        }

    </style>
</head>
<body>

    <div class="wrapper">
        <div class="sidebar">
            <a href="dashboard.php">Dashboard</a>
            <a href="editprofile.php">Edit Profile</a>
            <a href="address.php">Addresses</a>
            <a href="ordhistory.php">Orders</a><hr>
            <a href="logout.php">Logout</a>
        </div>
        <div class="content">
            <h2>Addresses</h2>

            <!-- Display added addresses here -->
            <div id="addressList">
                <!-- Addresses will be dynamically added here -->
            </div>

            <!-- Button to add new address -->
            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                Add Address
            </button>

            <!-- Modal for adding/editing address -->
            <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addAddressModalLabel">Add Address</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form for adding/editing address -->
                            <form id="addressForm">
                                <input type="hidden" id="addressId" name="addressId">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="mb-3">
                                    <label for="brgy" class="form-label">Barangay</label>
                                    <input type="text" class="form-control" id="brgy" name="brgy">
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="defaultAddress" name="defaultAddress">
                                    <label class="form-check-label" for="defaultAddress">
                                        Set as Default Address
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary" id="saveAddressBtn">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data for demonstration (replace with actual data handling)
        let addresses = [];

        // Function to render addresses from the array
        function renderAddresses() {
            let addressList = document.getElementById('addressList');
            addressList.innerHTML = '';

            addresses.forEach(address => {
                let addressBlock = document.createElement('div');
                addressBlock.classList.add('address-container'); // Correct class name here
                addressBlock.dataset.addressId = address.id; // Store address ID in data attribute
                addressBlock.innerHTML = `
                    <p><strong>Address:</strong> ${address.address}</p>
                    <p><strong>City:</strong> ${address.city}</p>
                    <p><strong>Barangay:</strong> ${address.brgy}</p>
                    <button type="button" class="btn btn-sm btn-primary edit-address-btn" data-bs-toggle="modal" data-bs-target="#addAddressModal">Edit</button>
                    <button type="button" class="btn btn-sm btn-danger delete-address-btn">Delete</button>
                    <button type="button" class="btn btn-sm btn-secondary set-default-address-btn">Set as Default</button>
                `;
                addressList.appendChild(addressBlock);
            });

            // Attach event listeners to dynamically created buttons
            document.querySelectorAll('.edit-address-btn').forEach(button => {
                button.addEventListener('click', function() {
                    let addressId = this.closest('.address-container').dataset.addressId;
                    populateAddressForm(addressId);
                });
            });

            document.querySelectorAll('.delete-address-btn').forEach(button => {
                button.addEventListener('click', function() {
                    let addressId = this.closest('.address-container').dataset.addressId;
                    deleteAddress(addressId);
                });
            });

            document.querySelectorAll('.set-default-address-btn').forEach(button => {
                button.addEventListener('click', function() {
                    let addressId = this.closest('.address-container').dataset.addressId;
                    setDefaultAddress(addressId);
                });
            });
        }

        // Function to populate form fields with address data for editing
        function populateAddressForm(addressId) {
            let address = addresses.find(a => a.id === addressId);
            if (address) {
                document.getElementById('addressId').value = address.id;
                document.getElementById('address').value = address.address;
                document.getElementById('city').value = address.city;
                document.getElementById('brgy').value = address.brgy;
                document.getElementById('defaultAddress').checked = address.isDefault;
                document.getElementById('saveAddressBtn').innerText = 'Update Address';
            }
        }

        // Function to handle form submission (add/update address)
        document.getElementById('addressForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let addressId = document.getElementById('addressId').value;
            let address = document.getElementById('address').value;
            let city = document.getElementById('city').value;
            let brgy = document.getElementById('brgy').value;
            let isDefault = document.getElementById('defaultAddress').checked;

            if (addressId) {
                // Update existing address
                let index = addresses.findIndex(a => a.id === addressId);
                if (index !== -1) {
                    addresses[index] = { id: addressId, address: address, city: city, brgy: brgy, isDefault: isDefault };
                }
            } else {
                // Add new address
                let newAddress = { id: Date.now().toString(), address: address, city: city, brgy: brgy, isDefault: isDefault };
                addresses.push(newAddress);
            }

            renderAddresses();
            resetForm();
            let modal = bootstrap.Modal.getInstance(document.getElementById('addAddressModal'));
            modal.hide();
        });

        // Function to delete an address
        function deleteAddress(addressId) {
            addresses = addresses.filter(a => a.id !== addressId);
            renderAddresses();
        }

        // Function to set an address as default
        function setDefaultAddress(addressId) {
            addresses.forEach(a => {
                if (a.id === addressId) {
                    a.isDefault = true;
                } else {
                    a.isDefault = false;
                }
            });
            renderAddresses();
        }

        // Function to reset form fields after submission
        function resetForm() {
            document.getElementById('addressId').value = '';
            document.getElementById('address').value = '';
            document.getElementById('city').value = '';
            document.getElementById('brgy').value = '';
            document.getElementById('defaultAddress').checked = false;
            document.getElementById('saveAddressBtn').innerText = 'Save Address';
        }

        // Initial rendering of addresses (replace with actual data retrieval)
        renderAddresses();
    </script>
</body>
</html>