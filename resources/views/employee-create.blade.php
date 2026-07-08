<div class="modal fade" id="createEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; height: 90vh;">
            <div class="modal-header" style="background: linear-gradient(135deg, #0B1E3D 0%, #112952 100%); border: none; padding: 1.5rem 2rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 44px; height: 44px; background: rgba(245,158,11,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-plus-fill" style="font-size: 1.4rem; color: #F59E0B;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" style="color: white; font-weight: 700; font-size: 1.25rem;">Create New Employee</h5>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.8rem; margin: 0;">Fill in the details to add a new team member</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>

            <form action="{{ url('registerEmployee') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; height: 100%;">
                @csrf
                <div class="modal-body" style="padding: 1.5rem 2rem; flex: 1; overflow-y: auto;">
                    <div class="row" style="margin: 0;">
                        <!-- Left Column - Basic Info -->
                        <div class="col-md-6" style="padding: 0 1rem;">
                            <h6 style="color: #0B1E3D; font-weight: 700; margin-bottom: 1.25rem; padding-bottom: 0.5rem; border-bottom: 2px solid #F59E0B;">
                                <i class="bi bi-person-badge me-2"></i>Basic Information
                            </h6>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Full Name *</label>
                                <input type="text" class="form-control" name="fname" required placeholder="Enter full name" style="border-radius: 10px; padding: 0.7rem;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Email Address *</label>
                                <input type="email" class="form-control" name="email" required placeholder="employee@company.com" style="border-radius: 10px; padding: 0.7rem;">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Contact Number</label>
                                    <input type="tel" class="form-control" name="contact" placeholder="+255 XXX XXX XXX" style="border-radius: 10px; padding: 0.7rem;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Age</label>
                                    <input type="number" class="form-control" name="age" placeholder="25" style="border-radius: 10px; padding: 0.7rem;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Password *</label>
                                    <input type="password" class="form-control" id="password" name="password1" required placeholder="Create password" style="border-radius: 10px; padding: 0.7rem;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Confirm Password *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="password2" required placeholder="Confirm password" style="border-radius: 10px; padding: 0.7rem;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Profile Photo</label>
                                <input type="file" class="form-control" name="photo" accept="image/*" style="border-radius: 10px; padding: 0.5rem;">
                                <small class="text-muted">Accepted formats: JPG, PNG (Max 2MB)</small>
                            </div>
                        </div>

                        <!-- Right Column - Role & Permissions -->
                        <div class="col-md-6" style="padding: 0 1rem;">
                            <h6 style="color: #0B1E3D; font-weight: 700; margin-bottom: 1.25rem; padding-bottom: 0.5rem; border-bottom: 2px solid #F59E0B;">
                                <i class="bi bi-shield-lock me-2"></i>Role & Permissions
                            </h6>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Employee Role *</label>
                                <select class="form-select" name="level" required id="roleSelect" style="border-radius: 10px; padding: 0.7rem;">
                                    <option value="" disabled selected>Select role</option>
                                    <option value="Admin">Admin - Full system access</option>
                                    <option value="Manager">Manager - Manage operations</option>
                                    <option value="Seller">Seller - Sales only</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Assign to Shops</label>
                                <div style="border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 0.75rem; max-height: 120px; overflow-y: auto;">
                                    @if(isset($accounts) && count($accounts) > 0)
                                        @foreach($accounts as $account)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="accounts[]" value="{{ $account['id'] }}" id="shop_{{ $account['id'] }}" style="border-radius: 4px;">
                                                <label class="form-check-label" for="shop_{{ $account['id'] }}" style="font-weight: 500;">
                                                    {{ $account['name'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="accounts[]" value="{{ getCurrentShopId() }}" checked>
                                            <label class="form-check-label">Current Shop</label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Permissions Section -->
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Permissions</label>
                                <div style="border: 1.5px solid #E2E8F0; border-radius: 12px; max-height: 320px; overflow-y: auto; background: white;" id="permissionsContainer">

                                    <!-- Inventory Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="inventory" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-box-seam" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Inventory Management</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8; margin-left: 0.5rem;">(4 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_items" id="perm_view_items">
                                                <label class="form-check-label" for="perm_view_items">View Items</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_items" id="perm_create_items">
                                                <label class="form-check-label" for="perm_create_items">Create Items</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_products" id="perm_edit_products">
                                                <label class="form-check-label" for="perm_edit_products">Edit Products</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_products" id="perm_delete_products">
                                                <label class="form-check-label" for="perm_delete_products">Delete Products</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="Request" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-box-seam" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Item Request Management</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8; margin-left: 0.5rem;">(2 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="item_request" id="perm_item_request">
                                                <label class="form-check-label" for="perm_item_request">Item Requests</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="item_request_date" id="perm_item_request_date">
                                                <label class="form-check-label" for="perm_item_request_date">Item Request Date</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_item_requests" id="perm_view_item_requests">
                                                <label class="form-check-label" for="perm_view_item_requests">View Items Requests</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="manage_item_request" id="perm_manage_item_request">
                                                <label class="form-check-label" for="perm_manage_item_request">Manage Item Requests</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_item_request" id="perm_delete_item_request">
                                                <label class="form-check-label" for="perm_delete_item_request">Delete Item Requests</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Sales Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="sales" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-cart" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Sales Management</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(3 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_sales" id="perm_view_sales">
                                                <label class="form-check-label" for="perm_view_sales">View Sales</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="create_sales" id="perm_create_sales">
                                                <label class="form-check-label" for="perm_create_sales">Create Sales</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_invoices" id="perm_view_invoices">
                                                <label class="form-check-label" for="perm_view_invoices">{{ __('messages.view_invoices') }}</label>
                                            </div>
                                        </div>
                                        </div>
                                    </div>

                                    <!-- Debts Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="debts" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-receipt" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Debts Management</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(4 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_shop_debts" id="perm_view_shop_debts">
                                                <label class="form-check-label" for="perm_view_shop_debts">{{ __('messages.view_shop_debts') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="edit_debts" id="perm_edit_debts">
                                                <label class="form-check-label" for="perm_edit_debts">Edit Shop Debts</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="manage_paid_invoice" id="perm_manage_paid_invoice">
                                                <label class="form-check-label" for="perm_manage_paid_invoice">{{ __('messages.manage_paid_invoice') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="pay_debts" id="perm_pay_debts">
                                                <label class="form-check-label" for="perm_pay_debts">Pay Debts</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Receivings & Returns -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="receivings" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-truck" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Receivings & Returns</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(3 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_receivings" id="perm_view_receivings">
                                                <label class="form-check-label" for="perm_view_receivings">{{ __('messages.view_receivings') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="approve_receiving" id="perm_approve_receiving">
                                                <label class="form-check-label" for="perm_approve_receiving">Approve Receivings</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="delete_receiving" id="perm_delete_receiving">
                                                <label class="form-check-label" for="perm_delete_receiving">Delete Receivings</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="undo_receiving" id="perm_undo_receiving">
                                                <label class="form-check-label" for="perm_undo_receiving">Undo Receivings</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="print_receiving" id="perm_print_receiving">
                                                <label class="form-check-label" for="perm_print_receiving">Print Receivings</label>
                                            </div>
                                              <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="make_return" id="perm_make_return">
                                                <label class="form-check-label" for="perm_make_return">{{ __('messages.make_return') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_returns" id="perm_view_returns">
                                                <label class="form-check-label" for="perm_view_returns">{{ __('messages.view_returns') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reports Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="reports" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-graph-up" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Reports</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(4 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_reports" id="perm_view_reports">
                                                <label class="form-check-label" for="perm_view_reports">View Reports</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_sales_report" id="perm_view_sales_report">
                                                <label class="form-check-label" for="perm_view_sales_report">Sales Report</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_stock_report" id="perm_view_stock_report">
                                                <label class="form-check-label" for="perm_view_stock_report">Stock Report</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_shops_report" id="perm_view_shops_report">
                                                <label class="form-check-label" for="perm_view_shops_report">Shops Report</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customers Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="customers" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-people" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Customers</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(2 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_customers" id="perm_view_customers">
                                                <label class="form-check-label" for="perm_view_customers">View Customers</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="add_customers" id="perm_add_customers">
                                                <label class="form-check-label" for="perm_add_customers">Add Customers</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_all_chips" id="perm_view_all_chips">
                                                <label class="form-check-label" for="perm_view_all_chips">View Chip</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Suppliers Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="suppliers" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-building" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">{{ __('messages.suppliers') }}</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(2 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_suppliers" id="perm_view_suppliers">
                                                <label class="form-check-label" for="perm_view_suppliers">{{ __('messages.view_suppliers') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="manage_supplier_credit" id="perm_manage_supplier_credit">
                                                <label class="form-check-label" for="perm_manage_supplier_credit">{{ __('messages.manage_supplier_credit') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="add_supplier" id="perm_add_supplier">
                                                <label class="form-check-label" for="perm_add_supplier">{{ __('messages.add_suppliers') }}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Expenses Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="expenses" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-receipt" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Expenses</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(2 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_expenses" id="perm_view_expenses">
                                                <label class="form-check-label" for="perm_view_expenses">View Expenses</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="manage_expenses" id="perm_manage_expenses">
                                                <label class="form-check-label" for="perm_manage_expenses">Manage Expenses</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Banking Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="banking" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-bank" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Banking</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(3 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_banking" id="perm_view_banking">
                                                <label class="form-check-label" for="perm_view_banking">View Banking</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="add_banking_supplier" id="perm_add_banking_supplier">
                                                <label class="form-check-label" for="perm_add_banking_supplier">Add Banking Supplier</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="add_banking_beneficiary" id="perm_add_banking_beneficiary">
                                                <label class="form-check-label" for="perm_add_banking_beneficiary">Add Beneficiary</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Store Permissions -->
                                    <div class="perm-group" style="margin: 0; border-bottom: 1px solid #E2E8F0;">
                                        <div class="perm-group-head" data-group="mainstore" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-shop" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">Main Store Section</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(13 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_main_store" id="perm_view_main_store">
                                                <label class="form-check-label" for="perm_view_main_store">View Main Store Section</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_items" id="perm_main_view_items">
                                                <label class="form-check-label" for="perm_main_view_items">View Items</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_create_items" id="perm_main_create_items">
                                                <label class="form-check-label" for="perm_main_create_items">Create Items</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_create_item_request" id="perm_main_create_item_request">
                                                <label class="form-check-label" for="perm_main_create_item_request">Create Item Request</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_item_request" id="perm_main_view_item_request">
                                                <label class="form-check-label" for="perm_main_view_item_request">View Item Request</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_item_reports" id="perm_main_view_item_reports">
                                                <label class="form-check-label" for="perm_main_view_item_reports">View Item Reports</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_receiving" id="perm_main_view_receiving">
                                                <label class="form-check-label" for="perm_main_view_receiving">{{ __('messages.view_receivings') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_returns" id="perm_main_view_returns">
                                                <label class="form-check-label" for="perm_main_view_returns">{{ __('messages.view_returns') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_make_receiving" id="perm_main_make_receiving">
                                                <label class="form-check-label" for="perm_main_make_receiving">{{ __('messages.make_receiving') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_make_return" id="perm_main_make_return">
                                                <label class="form-check-label" for="perm_main_make_return">{{ __('messages.make_return') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_supplier_credit" id="perm_main_supplier_credit">
                                                <label class="form-check-label" for="perm_main_supplier_credit">{{ __('messages.supplier_credit') }}</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_customers" id="perm_main_view_customers">
                                                <label class="form-check-label" for="perm_main_view_customers">View Customers</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_main_store_report" id="perm_view_main_store_report">
                                                <label class="form-check-label" for="perm_view_main_store_report">Main Store Report</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- System Permissions -->
                                    <div class="perm-group" style="margin: 0;">
                                        <div class="perm-group-head" data-group="system" onclick="togglePermissionGroup(this)">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="bi bi-gear" style="color: #F59E0B; font-size: 1.1rem;"></i>
                                                <strong style="color: #0B1E3D;">System Access</strong>
                                                <span style="font-size: 0.7rem; color: #94A3B8;">(4 permissions)</span>
                                            </div>
                                            <i class="bi bi-chevron-down" style="color: #94A3B8; transition: transform 0.2s;"></i>
                                        </div>
                                        <div class="perm-group-body" style="padding: 0 1rem 0.75rem 1rem; ">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_logs" id="perm_view_logs">
                                                <label class="form-check-label" for="perm_view_logs">View Logs</label>
                                            </div>
                                             <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_settings" id="perm_view_settings">
                                                <label class="form-check-label" for="perm_view_settings">View Settings</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="manage_employees" id="perm_manage_employees">
                                                <label class="form-check-label" for="perm_manage_employees">Manage Employees</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="manage_language" id="perm_manage_language">
                                                <label class="form-check-label" for="perm_manage_language">Manage Language</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                </div>

                <div class="modal-footer" style="padding: 1rem 2rem; border-top: 1px solid #E2E8F0; background: #F8FAFC;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 0.6rem 1.5rem;">Cancel</button>
                    <button type="submit" class="btn" style="background: #F59E0B; color: #0B1E3D; border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 600;">
                        <i class="bi bi-save me-2"></i>Create Employee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .perm-group-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        padding: 0.85rem 1rem;
        background: #F8FAFC;
        transition: all 0.2s;
        border-radius: 0;
    }
    .perm-group-head:hover {
        background: #E8EDF5 !important;
    }
    .perm-group-body {
        background: white;
    }
    .form-check-input:checked {
        background-color: #F59E0B;
        border-color: #F59E0B;
    }
    .form-control:focus, .form-select:focus {
        border-color: #F59E0B;
        box-shadow: 0 0 0 0.2rem rgba(245,158,11,0.1);
    }
    /* Custom scrollbar */
    #permissionsContainer::-webkit-scrollbar {
        width: 6px;
    }
    #permissionsContainer::-webkit-scrollbar-track {
        background: #F1F5F9;
        border-radius: 3px;
    }
    #permissionsContainer::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 3px;
    }
    #permissionsContainer::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }
</style>

<script>
    // Global function to toggle permission groups
    function togglePermissionGroup(element) {
        const body = element.nextElementSibling;
        const icon = element.querySelector('.bi-chevron-down, .bi-chevron-up');

        if (!body) return;

        if (getComputedStyle(body).display === 'none') {
            body.style.display = 'block';
            if (icon) icon.className = 'bi bi-chevron-up';
        } else {
            body.style.display = 'none';
            if (icon) icon.className = 'bi bi-chevron-down';
        }
    }

    // Initialize everything when modal is shown
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for modal show event
        const modal = document.getElementById('createEmployeeModal');

        if (modal) {
            modal.addEventListener('shown.bs.modal', function() {
                // Reset all permission bodies to hidden
                const allBodies = document.querySelectorAll('#createEmployeeModal .perm-group-body');
                allBodies.forEach(body => {
                    body.style.display = 'block';
                });

                // Reset all icons to chevron-down
                const allIcons = document.querySelectorAll('#createEmployeeModal .perm-group-head i.bi');
                allIcons.forEach(icon => {
                    icon.className = 'bi bi-chevron-down';
                });
            });

            // Initialize password validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            if (password && confirmPassword) {
                function validate() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity("Passwords don't match");
                    } else {
                        confirmPassword.setCustomValidity('');
                    }
                }
                password.addEventListener('change', validate);
                confirmPassword.addEventListener('keyup', validate);
            }

            // Role select handler
            const roleSelect = document.getElementById('roleSelect');
            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    const role = this.value;
                    const checkboxes = document.querySelectorAll('#createEmployeeModal input[name="permissions[]"]');

                    if (role === 'Admin') {
                        checkboxes.forEach(cb => cb.checked = true);
                    } else if (role === 'Manager') {
                        const managerPerms = ['view_items', 'view_sales', 'create_sales', 'view_invoices', 'view_reports', 'view_receivings', 'view_customers', 'view_suppliers'];
                        checkboxes.forEach(cb => {
                            cb.checked = managerPerms.includes(cb.value);
                        });
                    } else if (role === 'Seller') {
                        const sellerPerms = ['view_sales', 'create_sales', 'view_customers'];
                        checkboxes.forEach(cb => {
                            cb.checked = sellerPerms.includes(cb.value);
                        });
                    } else {
                        checkboxes.forEach(cb => cb.checked = false);
                    }
                });
            }
        }
    });
</script>