<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e(config("app.name")); ?> - <?php echo e(__('messages.request_details')); ?></title>
    <?php echo $__env->make("links", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0B1E3D;
            --navy-mid: #112952;
            --navy-light: #1A3A6B;
            --amber: var(--theme-primary);
            --amber-pale: #FEF3C7;
            --emerald: #059669;
            --emerald-pale: #D1FAE5;
            --rose: #E11D48;
            --rose-pale: #FFE4E6;
            --violet: #7C3AED;
            --violet-pale: #EDE9FE;
            --sky: #0284C7;
            --sky-pale: #E0F2FE;
            --slate-50: #F8FAFC;
            --slate-100: #F1F5F9;
            --slate-200: #E2E8F0;
            --slate-300: #CBD5E1;
            --slate-400: #94A3B8;
            --slate-500: #64748B;
            --slate-600: #475569;
            --slate-700: #334155;
            --slate-800: #1E293B;
            --white: #FFFFFF;
            --radius: 12px;
            --shadow-sm: 0 1px 3px rgba(11,30,61,.08);
            --shadow: 0 4px 20px rgba(11,30,61,.12);
            --shadow-lg: 0 10px 40px rgba(11,30,61,.15);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--slate-50);
            color: var(--slate-800);
        }

        /* ===== ALERTS ===== */
        .alert {
            margin: 0 2rem 1rem;
            border-radius: var(--radius);
            border: none;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .alert-success {
            background: var(--emerald-pale);
            color: var(--emerald);
            border-left: 4px solid var(--emerald);
        }
        .alert-danger {
            background: var(--rose-pale);
            color: var(--rose);
            border-left: 4px solid var(--rose);
        }
        .alert .btn-close {
            filter: none;
            opacity: 0.6;
        }
        .alert .btn-close:hover {
            opacity: 1;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: var(--white);
            border-bottom: 1px solid var(--slate-200);
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--slate-500);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            padding: .4rem .75rem;
            border-radius: var(--radius);
            transition: all .15s;
        }
        .back-btn:hover { background: var(--slate-100); color: var(--navy); }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--navy);
        }

        .page-title small {
            font-family: 'DM Sans', sans-serif;
            font-size: .8rem;
            font-weight: 400;
            color: var(--slate-500);
            margin-left: 0.5rem;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1.2rem;
            border-radius: var(--radius);
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .2s ease;
            white-space: nowrap;
        }
        .btn-primary { background: var(--navy); color: var(--white); }
        .btn-primary:hover { background: var(--navy-light); color: var(--white); transform: translateY(-2px); box-shadow: var(--shadow); }
        
        .btn-success { background: var(--emerald); color: var(--white); }
        .btn-success:hover { background: #047857; color: var(--white); transform: translateY(-2px); box-shadow: var(--shadow); }
        
        .btn-outline { background: transparent; color: var(--slate-700); border: 1px solid var(--slate-200); }
        .btn-outline:hover { background: var(--slate-100); border-color: var(--navy); }
        
        .btn-ghost { background: transparent; color: var(--navy); border: 1px solid rgba(11,30,61,.15); }
        .btn-ghost:hover { background: var(--slate-100); border-color: var(--navy); }
        
        .btn-danger { background: var(--rose); color: var(--white); }
        .btn-danger:hover { background: #BE123C; color: var(--white); transform: translateY(-2px); box-shadow: var(--shadow); }
        
        .btn-sm { padding: .35rem .75rem; font-size: .8rem; }
        .btn-warning { background: var(--amber); color: var(--navy); }
        .btn-warning:hover { background: #d97706; color: var(--navy); transform: translateY(-2px); }

        /* ===== CARD ===== */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--slate-200);
            box-shadow: var(--shadow-sm);
            margin: 1.5rem 2rem;
            overflow: hidden;
            transition: all .3s ease;
        }
        .card:hover {
            box-shadow: var(--shadow);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            background: var(--slate-50);
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .card-header h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            color: var(--navy);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ===== INFO GRID ===== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--slate-200);
        }

        .info-item {
            background: var(--slate-50);
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            border: 1px solid var(--slate-100);
        }

        .info-item label {
            display: block;
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--slate-500);
            margin-bottom: .25rem;
            font-weight: 600;
        }

        .info-item span {
            font-size: 1rem;
            font-weight: 600;
            color: var(--slate-800);
        }

        /* ===== PILLS ===== */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .3rem .8rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .pill-pending { background: var(--amber-pale); color: #92400e; }
        .pill-approved { background: var(--emerald-pale); color: var(--emerald); }
        .pill-rejected { background: var(--rose-pale); color: var(--rose); }
        .pill-submitted { background: var(--sky-pale); color: var(--sky); }
        .pill-mixed { background: var(--violet-pale); color: var(--violet); }
        
        .pill::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: .6;
            display: inline-block;
        }

        .stock-available {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            background: var(--emerald-pale);
            color: var(--emerald);
        }
        .stock-available::before {
            content: '✓';
            font-weight: 700;
        }
        .stock-unavailable {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .25rem .65rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            background: var(--rose-pale);
            color: var(--rose);
        }
        .stock-unavailable::before {
            content: '✗';
            font-weight: 700;
        }

        /* ===== TABLE ===== */
        .table-wrapper {
            overflow-x: auto;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table thead tr {
            background: var(--navy);
        }

        .details-table th {
            padding: .85rem 1rem;
            text-align: left;
            font-size: .7rem;
            font-weight: 600;
            color: rgba(255,255,255,.8);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .details-table td {
            padding: .85rem 1rem;
            border-bottom: 1px solid var(--slate-100);
            font-size: .875rem;
            vertical-align: middle;
        }

        .details-table tbody tr:last-child td {
            border-bottom: none;
        }
        .details-table tbody tr:hover td {
            background: var(--slate-50);
        }

        /* ===== TOTALS ===== */
        .totals {
            margin-top: 1.5rem;
            padding: 1.25rem 1.5rem;
            background: var(--slate-50);
            border-radius: var(--radius);
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
            border: 1px solid var(--slate-200);
        }

        .totals div label {
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--slate-500);
            display: block;
            margin-bottom: .2rem;
            font-weight: 600;
        }

        .totals div span {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--navy);
        }

        .totals div:last-child span {
            color: var(--emerald);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .card {
                margin: 1rem;
            }
            .page-header {
                padding: 1rem;
            }
            .page-title {
                font-size: 1rem;
            }
            .info-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            .info-item {
                padding: 0.5rem 0.75rem;
            }
            .totals {
                gap: 1rem;
            }
            .totals div span {
                font-size: 1rem;
            }
            .alert {
                margin: 0 1rem 0.75rem;
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <?php echo $__env->make("sidenav", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-0 pt-3 bg-light">
              
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <span><i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <span><i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('error')); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="page-header">
                <div class="page-header-left">
                    <a href="<?php echo e(url('viewRequest')); ?>" class="back-btn">
                        <i class="bi bi-chevron-left"></i> <?php echo e(__('messages.back_to_requests')); ?>

                    </a>
                    <span class="page-title">
                        <?php echo e(__('messages.request_details')); ?>

                        <small>#<?php echo e($requestId); ?></small>
                    </span>
                </div>
                <div>
                    <?php if($isAdmin && $overallStatus !== 'Approved'): ?>
                        <form method="post" action="/request/approve-all" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="requestName" value="<?php echo e($requestId); ?>">
                            <input type="hidden" name="supplierId" value="<?php echo e($supplierAccount); ?>">
                            <input type="hidden" name="status" value="approve">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-all"></i> <?php echo e(__('messages.approve_all')); ?>

                            </button>
                        </form>
                    <?php endif; ?>
                    <a href="<?php echo e(url('viewRequest')); ?>" class="btn btn-outline">
                        <i class="bi bi-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3><i class="bi bi-clipboard-data me-2" style="color: var(--amber);"></i> <?php echo e(__('messages.request_summary')); ?></h3>
                    <span class="pill <?php echo e($pillMap[$overallStatus] ?? 'pill-mixed'); ?>"><?php echo e($overallStatus); ?></span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label><i class="bi bi-hash"></i> <?php echo e(__('messages.request_id')); ?></label>
                            <span><?php echo e($requestId); ?></span>
                        </div>
                        <div class="info-item">
                            <label><i class="bi bi-calendar3"></i> <?php echo e(__('messages.date')); ?></label>
                            <span><?php echo e(date('F d, Y', strtotime($items[0]->created_at ?? now()))); ?></span>
                        </div>
                        <div class="info-item">
                            <label><i class="bi bi-arrow-right-circle"></i> <?php echo e(__('messages.from_shop')); ?></label>
                            <span><?php echo e($requesterName ?? __('messages.na')); ?></span>
                        </div>
                        <div class="info-item">
                            <label><i class="bi bi-arrow-left-circle"></i> <?php echo e(__('messages.to_shop')); ?></label>
                            <span><?php echo e($supplierName ?? __('messages.na')); ?></span>
                        </div>
                        <div class="info-item">
                            <label><i class="bi bi-credit-card"></i> <?php echo e(__('messages.payment_type')); ?></label>
                            <span><?php echo e(ucfirst($items[0]->payment_type ?? 'Cash')); ?></span>
                        </div>
                        <div class="info-item">
                            <label><i class="bi bi-person"></i> <?php echo e(__('messages.assigned_to')); ?></label>
                            <span><?php echo e($items[0]->assignedToName ?? ($items[0]->assigned_to ?? __('messages.na'))); ?></span>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.col_product')); ?></th>
                                    <th><?php echo e(__('messages.products_col_product_id')); ?></th>
                                    <th style="text-align: center;"><?php echo e(__('messages.col_qty')); ?></th>
                                    <th style="text-align: right;"><?php echo e(__('messages.price')); ?></th>
                                    <th style="text-align: right;"><?php echo e(__('messages.total')); ?></th>
                                    <th><?php echo e(__('messages.stock_status')); ?></th>
                                    <th><?php echo e(__('messages.item_status')); ?></th>
                                    <th style="text-align: center;"><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $formattedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $prodQuant = DB::table('products')
                                            ->where('product_id', $item->productId)
                                            ->where('account', 7)
                                            ->value('quantity') ?? 0;
                                        
                                        $adjustedQuantity = $item->adjustedQuantity ?? $item->quantity;
                                        $returnedQuantity = $item->returnedQuantity ?? 0;
                                        $itemTotal = $adjustedQuantity * $item->price;
                                        
                                        $stockBadge = ($prodQuant >= $adjustedQuantity)
                                            ? '<span class="stock-available">' . __('messages.in_stock', ['count' => number_format($prodQuant)]) . '</span>'
                                            : '<span class="stock-unavailable">' . __('messages.out_of_stock_need', ['count' => number_format($adjustedQuantity - $prodQuant)]) . '</span>';
                                        
                                        $itemPillMap = [
                                            'Pending' => 'pill-pending',
                                            'Approved' => 'pill-approved',
                                            'Rejected' => 'pill-rejected',
                                            'Submitted' => 'pill-submitted',
                                        ];
                                        $itemPillClass = $itemPillMap[$item->status] ?? 'pill-mixed';
                                    ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>
                                            <form action="/viewProduct" method="post">
                                                <?php echo csrf_field(); ?>
                                            <button name="product_id" value="<?php echo e($item->productId); ?>" class="btn border-0">
                                                <strong style="color: var(--navy);"><?php echo e($item->productName); ?></strong>
                                            </button>
                                            </form>
                                        </td>
                                        <td><span style="color: var(--slate-500); font-size: .8rem;"><?php echo e($item->productId ?? __('messages.na')); ?></span></td>
                                        <td style="text-align: center;">
                                            <?php echo e(number_format($adjustedQuantity)); ?>

                                            <?php if($returnedQuantity > 0): ?>
                                                <br><small style="color: var(--rose);">(-<?php echo e(number_format($returnedQuantity)); ?> returned)</small>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align: right;">Tsh <?php echo e(number_format($item->price)); ?></td>
                                        <td style="text-align: right; font-weight: 700; color: var(--navy);">Tsh <?php echo e(number_format($itemTotal)); ?></td>
                                        <td><?php echo $stockBadge; ?></td>
                                        <td><span class="pill <?php echo e($itemPillClass); ?>"><?php echo e($item->status); ?></span></td>
                                        <td style="text-align: center;">
                                            <?php if($isAdmin && ($item->status !== 'Approved') && $item->status !== 'Rejected'): ?>
                                                <div style="display: flex; gap: 4px; justify-content: center;">
                                                    <form method="post" action="/approveRequest" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="requestName" value="<?php echo e($item->id); ?>">
                                                        <input type="hidden" name="product_id" value="<?php echo e($item->productId); ?>">
                                                        <input type="hidden" name="supplierId" value="<?php echo e($supplierAccount); ?>">
                                                        <button type="submit" class="btn btn-success btn-sm" title="<?php echo e(__('messages.approve_request')); ?>">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                    <form method="post" action="/rejectRequest" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="requestName" value="<?php echo e($requestId); ?>">
                                                        <input type="hidden" name="product_id" value="<?php echo e($item->productId); ?>">
                                                        <input type="hidden" name="supplierId" value="<?php echo e($supplierAccount); ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" title="<?php echo e(__('messages.reject_request')); ?>">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                     <div class="totals">
                         <div>
                             <label><i class="bi bi-box"></i> <?php echo e(__('messages.total_items')); ?></label>
                             <span><?php echo e(count($formattedItems)); ?></span>
                         </div>
                         <div>
                             <label><i class="bi bi-cubes"></i> <?php echo e(__('messages.total_quantity')); ?></label>
                             <span><?php echo e(number_format($totalQuantity)); ?></span>
                         </div>
                         <div>
                             <label><i class="bi bi-cubes"></i> <?php echo e(__('messages.adjusted_total_quantity')); ?></label>
                             <span><?php echo e(number_format($adjustedTotalQuantity)); ?></span>
                         </div>
                         <div>
                             <label><i class="bi bi-arrow-return-left"></i> <?php echo e(__('messages.total_returned')); ?></label>
                             <span><?php echo e(number_format($totalReturnedQuantity)); ?></span>
                         </div>
                         <div>
                             <label><i class="bi bi-cash"></i> <?php echo e(__('messages.total_price')); ?></label>
                             <span>Tsh <?php echo e(number_format($totalPrice)); ?></span>
                         </div>
                         <div>
                             <label><i class="bi bi-cash"></i> <?php echo e(__('messages.unhandled_price')); ?></label>
                             <span>Tsh <?php echo e(number_format($unhandledPrice)); ?></span>
                         </div>
                         <div>
                             <label><i class="bi bi-cash"></i> <?php echo e(__('messages.grand_total')); ?></label>
                             <span>Tsh <?php echo e(number_format($totalPrice - $unhandledPrice)); ?></span>
                         </div>
                     </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/viewRequestDetails.blade.php ENDPATH**/ ?>