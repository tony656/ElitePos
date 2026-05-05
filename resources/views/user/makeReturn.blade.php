<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Return</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary-color: #004E89; --secondary-color: #1a659e; --success-color: #28a745; --danger-color: #dc3545; --text-dark: #343a40; --text-light: #6c757d; --border-radius: 8px; --shadow: 0 2px 8px rgba(0,0,0,0.06); --transition: all 0.2s ease; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; zoom: 1.1; }
        .main-container { max-width: 1900px; margin: 0 auto; padding: 0.75rem; }
        .page-header { background: white; padding: 0.9rem 1.2rem; border-radius: var(--border-radius); margin-bottom: 0.75rem; box-shadow: var(--shadow); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
        .page-title { font-size: 1.4rem; font-weight: 700; color: var(--danger-color); display: flex; align-items: center; gap: 0.4rem; }
        .warning-banner { background: #fff5f5; border: 2px solid var(--danger-color); border-radius: var(--border-radius); padding: 0.75rem 1rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.75rem; }
        .warning-banner i { color: var(--danger-color); font-size: 1.5rem; }
        .warning-banner strong { color: var(--danger-color); }
        .main-layout { display: grid; grid-template-columns: 70% 30%; gap: 0.75rem; height: calc(100vh - 220px); }
        @media (max-width: 1200px) { .main-layout { grid-template-columns: 1fr; height: auto; } }
        .left-panel, .right-panel { background: white; border-radius: var(--border-radius); box-shadow: var(--shadow); display: flex; flex-direction: column; overflow: hidden; }
        .panel-header { background: linear-gradient(135deg, var(--danger-color), #c82333); color: white; padding: 0.75rem 1rem; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 0.4rem; }
        .panel-body { padding: 0.75rem; flex: 1; display: flex; flex-direction: column; overflow: hidden; gap: 0.75rem; }
        .product-search-section { flex-shrink: 0; position: relative; }
        .search-box { position: relative; }
        .search-box input { width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.2rem; border: 2px solid #e9ecef; border-radius: 6px; font-size: 0.9rem; }
        .search-box input:focus { border-color: var(--danger-color); outline: none; }
        .search-box i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-light); }
        .product-list-section { width: 100%; max-height: 400px; display: none; flex-direction: column; background: white; border-radius: var(--border-radius); box-shadow: 0 4px 16px rgba(0,0,0,0.15); z-index: 1000; border: 1px solid #e9ecef; }
        .product-list-section.active { display: flex; }
        .product-list-header { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; border-bottom: 1px solid #e9ecef; background: #fff5f5; }
        .product-list-title { font-weight: 600; color: var(--danger-color); }
        .product-count { background: var(--danger-color); color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.8rem; }
        .product-list { flex: 1; overflow-y: auto; }
        .product-item { padding: 0.6rem 1rem; border-bottom: 1px solid #e9ecef; cursor: pointer; transition: var(--transition); }
        .product-item:hover { background: #fff5f5; }
        .product-info { flex: 1; }
        .product-name { font-weight: 600; color: var(--text-dark); }
        .product-prices { font-size: 0.75rem; color: var(--text-light); }
        .supplier-section { padding: 0.75rem; border-bottom: 1px solid #e9ecef; flex-shrink: 0; }
        .form-group-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        .form-group { margin-bottom: 0; }
        .form-label { font-weight: 600; color: var(--text-dark); margin-bottom: 0.3rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.3rem; }
        .form-control, .form-select { border: 2px solid #e9ecef; border-radius: 6px; padding: 0.5rem 0.75rem; font-size: 0.9rem; }
        .cart-section { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .cart-header { padding: 0.75rem; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; }
        .cart-title { font-weight: 600; }
        .cart-count { background: var(--danger-color); color: white; padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.8rem; }
        .cart-items { flex: 1; overflow-y: auto; padding: 0.75rem; }
        .cart-items-table { width: 100%; border-collapse: collapse; }
        .cart-items-table th { padding: 0.5rem; text-align: left; font-size: 0.75rem; background: #fff5f5; border-bottom: 2px solid #e9ecef; color: var(--danger-color); }
        .cart-items-table td { padding: 0.4rem; font-size: 0.8rem; border-bottom: 1px solid #e9ecef; }
        .qty-cell input, .price-cell input { width: 100%; padding: 0.3rem; text-align: center; border: 1px solid #e9ecef; border-radius: 3px; font-size: 0.75rem; }
        .remove-item-table button { background: transparent; border: none; color: var(--danger-color); cursor: pointer; }
        .summary-section { padding: 0.75rem; border-top: 1px solid #e9ecef; background: #fff5f5; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 0.4rem; }
        .total-summary { background: linear-gradient(135deg, var(--danger-color), #c82333); color: white; padding: 0.8rem; border-radius: 6px; margin-top: 0.5rem; }
        .total-amount { font-size: 1.4rem; font-weight: 800; text-align: right; }
        .action-buttons { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; margin-top: 0.75rem; }
        .btn-primary-action { background: linear-gradient(135deg, var(--danger-color), #c82333); border: none; color: white; padding: 0.6rem; border-radius: 6px; font-weight: 700; cursor: pointer; }
        .btn-secondary-action { background: #f8f9fa; border: 2px solid #e9ecef; color: var(--text-dark); padding: 0.6rem; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center; color: var(--text-light); }
        .alert { border: none; border-radius: var(--border-radius); margin-bottom: 0.75rem; padding: 0.75rem 1rem; }
        .alert-success { background: rgba(40,167,69,0.1); border-left: 4px solid var(--success-color); }
        .alert-danger { background: rgba(220,53,69,0.1); border-left: 4px solid var(--danger-color); }
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .toast { background: white; border-radius: var(--border-radius); box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 0.75rem 1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; min-width: 300px; opacity: 0; transform: translateX(100%); transition: all 0.3s ease; }
        .toast.show { opacity: 1; transform: translateX(0); }
    </style>
</head>
<body>
    <div class="row">
        @include('user/sidenav')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="main-container">
                <div class="page-header">
                    <div class="page-title"><i class="bi bi-arrow-return-left"></i> Make Return</div>
                    <div class="d-flex gap-2">
                        <a href="{{ url('user/make-receiving') }}" class="btn btn-outline-primary"><i class="bi bi-plus-circle me-1"></i> Make Receiving</a>
                        <a href="{{ url('user/view-returns') }}" class="btn btn-outline-danger"><i class="bi bi-list-check me-1"></i> View Returns</a>
                    </div>
                </div>

                <div class="warning-banner">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div><strong>Warning: This will decrease product quantities!</strong><br><small>Returning products will reduce stock in your inventory.</small></div>
                </div>

                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                <div class="main-layout">
                    <div class="left-panel">
                        <div class="panel-header"><i class="bi bi-search"></i> Select Products to Return</div>
                        <div class="panel-body">
                            <div class="product-search-section">
                                <div class="search-box">
                                    <i class="bi bi-search"></i>
                                    <input type="text" id="productSearch" placeholder="Search products by name...">
                                </div>
                                <div class="product-list-section" id="productListSection">
                                    <div class="product-list-header">
                                        <div class="product-list-title">Available Products</div>
                                        <div class="product-count" id="productCount">0</div>
                                    </div>
                                    <div class="product-list" id="productList"></div>
                                </div>
                            </div>
                            <div class="cart-section" style="flex:1;">
                                <div class="cart-header">
                                    <div class="cart-title"><i class="bi bi-arrow-return-left"></i> Products to Return</div>
                                    <div class="cart-count" id="cartCount">0</div>
                                </div>
                                <div class="cart-items" id="cartItems">
                                    <div class="empty-state"><div class="empty-state-icon"><i class="bi bi-arrow-return-left"></i></div><div class="empty-state-title">No products added</div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="right-panel">
                        <div class="panel-header"><i class="bi bi-clipboard"></i> Return Details</div>
                        <div class="supplier-section">
                            <form id="orderForm">
                                @csrf
                                <div class="form-group-row">
                                    <div class="form-group">
                                        <label class="form-label"><i class="bi bi-shop"></i> Supplier</label>
                                        <select name="supplier" id="supplier" class="form-select" required>
                                            <option value="" disabled selected>Select Supplier</option>
                                            @foreach (DB::table('vendors')->get() as $vendor)<option value="{{ $vendor->name }}">{{ $vendor->name }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><i class="bi bi-person"></i> Processed By</label>
                                        <select name="served" id="served" class="form-select" required>
                                            <option value="" disabled selected>Select Staff</option>
                                            @foreach (DB::table('users')->get() as $user)
                                                @if($user->account === getSessionAccountDisplayName() || $user->levelStatus === 'Admin')<option value="{{ $user->name }}">{{ $user->name }}</option>@endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top:0.6rem;">
                                    <label class="form-label"><i class="bi bi-chat-text"></i> Reason for Return</label>
                                    <textarea name="reason" id="reason" class="form-control" rows="2" required placeholder="Specify reason..."></textarea>
                                </div>
                                <div class="form-group" style="margin-top:0.6rem;">
                                    <label class="form-label"><i class="bi bi-credit-card"></i> Payment Type</label>
                                    <select name="transactionType" id="transactionType" class="form-select">
                                        <option value="Cash">Cash</option>
                                        <option value="Credit">Credit</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="summary-section">
                            <div class="summary-item"><span>Total Items</span><span id="itemCount">0</span></div>
                            <div class="total-summary">
                                <div>TOTAL RETURN VALUE</div>
                                <div class="total-amount" id="totalAmount">Tsh. 0.00</div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary-action" id="clearCartBtn"><i class="bi bi-x-circle me-1"></i> Clear</button>
                                <button class="btn-primary-action" id="submitOrderBtn"><i class="bi bi-check-circle me-1"></i> Submit Return</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="toast-container" id="toastContainer"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
    const STORAGE_KEY = 'returnCartUser';
    function saveCartToStorage(){localStorage.setItem(STORAGE_KEY, JSON.stringify(shoppingCart));}
    function loadCartFromStorage(){try{const s=localStorage.getItem(STORAGE_KEY);if(s)shoppingCart=JSON.parse(s);}catch(e){shoppingCart=[];}}
    function clearCartStorage(){localStorage.removeItem(STORAGE_KEY);}
    const allProducts = [
        @if(DB::table('products')->where('name01','!=','')->where('account',getSessionAccountName())->count()>0)
        @foreach(DB::table('products')->where('name01','!=','')->where('account',getSessionAccountName())->get() as $p)
        {id:"{{$p->product_id}}",name:"{{addslashes($p->name01)}}",cost:{{$p->bPrice??0}},wholesale:{{$p->wholesale??0}},retail:{{$p->sPrice??0}},currentStock:{{$p->quantity??0}}},
        @endforeach
        @endif
    ];
    let shoppingCart=[];let lastSearchTerm='';
    document.addEventListener('DOMContentLoaded',function(){loadCartFromStorage();setupEventListeners();updateCounts();updateCartDisplay();updateSummary();});
    function setupEventListeners(){
        document.getElementById('productSearch').addEventListener('input',handleProductSearch);
        document.getElementById('clearCartBtn').addEventListener('click',clearCart);
        document.getElementById('submitOrderBtn').addEventListener('click',submitOrder);
        document.addEventListener('click',function(e){
            const s=document.getElementById('productListSection'),i=document.getElementById('productSearch');
            if(s&&!s.contains(e.target)&&i&&!i.contains(e.target))s.classList.remove('active');
        });
    }
    function handleProductSearch(e){
        const t=e.target.value.toLowerCase().trim(),s=document.getElementById('productListSection');
        if(t===''){s.classList.remove('active');document.getElementById('productCount').textContent='0';return;}
        s.classList.add('active');
        const f=allProducts.filter(p=>p.name.toLowerCase().includes(t));
        displayProducts(f);
    }
    function displayProducts(products){
        const l=document.getElementById('productList');
        if(products.length===0){l.innerHTML='<div class="empty-state">No products found</div>';document.getElementById('productCount').textContent='0';return;}
        l.innerHTML=products.map(p=>`<div class="product-item" onclick="handleProductClick('${p.id}',this)"><div class="product-info"><div class="product-name">${p.name}</div><div class="product-prices">Cost: ${p.cost.toLocaleString()} | Stock: ${p.currentStock}</div></div></div>`).join('');
        document.getElementById('productCount').textContent=products.length;
    }
    function handleProductClick(id){
        const p=allProducts.find(x=>x.id===id);
        if(!p)return;
        if(p.currentStock<1){showToast('Cannot return with zero stock!','error');return;}
        const e=shoppingCart.find(x=>x.productId===id);
        if(e){if(e.quantity<p.currentStock){e.quantity+=1;showToast(`Increased ${e.name}`);}else{showToast('Cannot exceed stock','error');return;}}
        else{shoppingCart.push({cartId:Date.now()+Math.random().toString(36).substr(2,9),productId:p.id,name:p.name,cost:p.cost,wholesale:p.wholesale,retail:p.retail,quantity:1,type:document.getElementById('transactionType')?.value||'Cash',expiry:''});showToast(`${p.name} added`);}
        saveCartToStorage();document.getElementById('productSearch').value='';document.getElementById('productListSection').classList.remove('active');
        updateCartDisplay();updateCounts();updateSummary();
    }
    function updateCartDisplay(){
        const c=document.getElementById('cartItems');
        if(shoppingCart.length===0){c.innerHTML='<div class="empty-state"><div class="empty-state-icon"><i class="bi bi-arrow-return-left"></i></div><div>No products added</div></div>';return;}
        c.innerHTML=`<table class="cart-items-table"><thead><tr><th>Product</th><th>Qty</th><th>Cost</th><th>Wholesale</th><th>Retail</th><th>Del</th></tr></thead><tbody>${shoppingCart.map(i=>`<tr><td>${i.name}</td><td class="qty-cell"><input type="number" value="${i.quantity}" min="1" oninput="updateQuantity('${i.cartId}',this.value);updateSummary();saveCartToStorage()"></td><td class="price-cell"><input type="number" value="${i.cost}" step="0.01" oninput="updatePrice('${i.cartId}','cost',this.value);updateSummary()"></td><td class="price-cell"><input type="number" value="${i.wholesale}" step="0.01" oninput="updatePrice('${i.cartId}','wholesale',this.value)"></td><td class="price-cell"><input type="number" value="${i.retail}" step="0.01" oninput="updatePrice('${i.cartId}','retail',this.value)"></td><td><button onclick="removeFromCart('${i.cartId}')"><i class="bi bi-trash"></i></button></td></tr>`).join('')}</tbody></table>`;
    }
    function updateQuantity(id,v){const i=shoppingCart.find(x=>x.cartId===id);if(i)i.quantity=parseInt(v)||1;}
    function updatePrice(id,t,v){const i=shoppingCart.find(x=>x.cartId===id);if(i){i[t]=parseFloat(v)||0;updateSummary();}}
    function removeFromCart(id){shoppingCart=shoppingCart.filter(x=>x.cartId!==id);saveCartToStorage();updateCartDisplay();updateCounts();updateSummary();showToast('Removed');}
    function clearCart(){if(shoppingCart.length===0)return;if(!confirm('Clear all?'))return;shoppingCart=[];clearCartStorage();updateCartDisplay();updateCounts();updateSummary();}
    function updateCounts(){document.getElementById('cartCount').textContent=shoppingCart.length;}
    function updateSummary(){let t=0,i=0;shoppingCart.forEach(x=>{t+=(x.cost||0)*(x.quantity||1);i+=x.quantity;});const fmt=n=>`Tsh. ${n.toLocaleString()}`;document.getElementById('itemCount').textContent=i;document.getElementById('totalAmount').textContent=fmt(t);}
    function submitOrder(){
        const sup=document.getElementById('supplier').value,srv=document.getElementById('served').value,reason=document.getElementById('reason').value;
        if(shoppingCart.length===0){showToast('Add products first','error');return;}
        if(!sup||!srv){showToast('Select supplier and staff','error');return;}
        if(!reason||reason.trim()===''){showToast('Provide a reason','error');return;}
        if(!confirm('This will decrease product quantities. Continue?'))return;
        const fd=new FormData();fd.append('_token','{{csrf_token()}}');fd.append('supplier',sup);fd.append('served',srv);fd.append('reason',reason);
        shoppingCart.forEach(i=>{fd.append('product_id[]',i.productId);fd.append('quantity[]',i.quantity);fd.append('bPrice[]',i.cost);fd.append('wholesale[]',i.wholesale);fd.append('sPrice[]',i.retail);fd.append('transactionType[]',i.type);fd.append('expiry[]','');});
        const btn=document.getElementById('submitOrderBtn');btn.innerHTML='Processing...';btn.disabled=true;
        fetch('{{route("user.process-return")}}',{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.text()).then(d=>{shoppingCart=[];clearCartStorage();updateCartDisplay();updateCounts();updateSummary();document.getElementById('orderForm').reset();showToast('Return processed! Quantities updated.');setTimeout(()=>location.href='{{url("user/view-returns")}}',1500);}).catch(()=>showToast('Error','error')).finally(()=>{btn.innerHTML='Submit Return';btn.disabled=false;});
    }
    function showToast(m,t='success'){let c=document.getElementById('toastContainer');if(!c){c=document.createElement('div');c.id='toastContainer';c.className='toast-container';document.body.appendChild(c);}const x=document.createElement('div');x.className=`toast toast-${t}`;x.innerHTML=m;c.appendChild(x);setTimeout(()=>x.classList.add('show'),10);setTimeout(()=>{x.classList.remove('show');setTimeout(()=>x.remove(),300);},3000);}
    </script>
</body>
</html>