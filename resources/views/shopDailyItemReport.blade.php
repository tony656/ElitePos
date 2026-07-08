<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.shop_daily_item_report') }} — {{ date('F d, Y', strtotime($fromDate ?? date('Y-m-d'))) }} to {{ date('F d, Y', strtotime($toDate ?? date('Y-m-d'))) }}</title>
    @include("links")
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F7F6F2;
            --surface: #FFFFFF;
            --border: #E4E2DA;
            --border-md: #CECCC4;
            --text: #0B1E3D;
            --muted: #7A7870;
            --accent: #F59E0B;
            --green: #1A6B45;
            --green-bg: #E6F4ED;
            --red: #B63A2F;
            --red-bg: #FDECEA;
            --amber: #F59E0B;
            --amber-bg: #FEF3D7;
            --purple: #1A3A6B;
            --purple-bg: #EEECFA;
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --radius: 10px;
            --radius-sm: 6px;
        }
        body { background: var(--bg); color: var(--text); font-size: 14px; line-height: 1.5; }
        .layout { display: flex; min-height: 100vh; }
        .sidebar-wrap { flex-shrink: 0; }
        .main { flex: 1; min-width: 0; padding: 2rem 2.5rem; }

        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap; background: #0B1E3D;
            color: #fff; padding: 20px; border-radius: 10px;
        }
        .page-title { font-size: 22px; font-weight: 600; letter-spacing: -0.3px; }
        .page-sub { font-size: 13px; color: var(--muted); margin-top: 3px; }

        .header-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

        .date-form { display: flex; border: 1px solid var(--border-md); border-radius: var(--radius-sm); overflow: hidden; background: var(--surface); }
        .date-form input[type=date] {
            border: none; outline: none; padding: 7px 12px; font-size: 13px;
            background: transparent; color: var(--text);
        }
        .date-form button {
            background: var(--accent); color: #fff; border: none;
            padding: 7px 14px; font-size: 13px; font-weight: 500;
            cursor: pointer; white-space: nowrap;
        }
        .date-form button:hover { opacity: 0.85; }

        .metrics { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; margin-bottom: 2rem; }
        @media (max-width: 900px) { .metrics { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .metrics { grid-template-columns: 1fr; } }

        .metric {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.1rem 1.25rem;
            box-shadow: var(--shadow);
        }
        .metric-label { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 8px; }
        .metric-value { font-size: 24px; font-weight: 600; letter-spacing: -0.5px; }
        .metric-sub { font-size: 12px; color: var(--muted); margin-top: 5px; }
        .metric.purple .metric-value { color: var(--purple); }
        .metric.green .metric-value { color: var(--green); }
        .metric.amber .metric-value { color: var(--amber); }

        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        .panel-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); gap: 1rem; flex-wrap: wrap; }
        .panel-title { font-size: 14px; font-weight: 600; }

        .tbl-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            background: var(--bg); color: var(--muted); font-size: 10.5px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.07em; padding: 9px 12px;
            text-align: right; white-space: nowrap; border-bottom: 1px solid var(--border);
        }
        thead th:nth-child(1), thead th:nth-child(2), thead th:nth-child(3) { text-align: left; }
        tbody tr { border-bottom: 1px solid var(--border); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #F9F8F4; }
        tbody tr.shop-group td { background: #F3F2EE; font-weight: 600; color: var(--text); }
        td { padding: 10px 12px; text-align: right; vertical-align: middle; }
        td:nth-child(1), td:nth-child(2), td:nth-child(3) { text-align: left; }
        .num { font-size: 12.5px; }
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 20px;
            font-size: 10.5px; font-weight: 600; letter-spacing: 0.03em;
            white-space: nowrap;
        }
        .badge-ok   { background: var(--green-bg); color: var(--green); }
        .badge-over { background: var(--amber-bg); color: var(--amber); }
        .badge-bad  { background: var(--red-bg);   color: var(--red);   }

        tfoot td {
            background: var(--bg); border-top: 2px solid var(--border-md);
            font-size: 12px; font-weight: 600; padding: 10px 12px; text-align: right; color: var(--muted);
        }
        tfoot td:nth-child(1), tfoot td:nth-child(2), tfoot td:nth-child(3) { text-align: left; color: var(--text); }

        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--muted); }
        .empty-state h4 { font-size: 16px; font-weight: 500; margin-bottom: 8px; color: var(--text); }
        .empty-state p { font-size: 13px; }

        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500; cursor: pointer;
            border: 1px solid var(--border-md); background: var(--surface); color: var(--text);
            text-decoration: none; transition: background 0.15s;
        }
        .btn-back:hover { background: var(--bg); }

        @media(max-width:768px) {
            .main { padding: 1rem; }
            .page-header { padding: 0.75rem 1rem; }
        }
    </style>
</head>
<body>
@include('sidenav')

    <main class="main-content">
        <div class="main">
            <div class="page-header">
                <div>
                    <div class="page-title">{{ __('messages.shop_daily_item_report') }}</div>
                    <div class="page-sub">{{ __('messages.shop_daily_item_report_sub') ?? 'Requested, received and sold items per shop' }} — {{ date('F d, Y', strtotime($fromDate ?? date('Y-m-d'))) }} to {{ date('F d, Y', strtotime($toDate ?? date('Y-m-d'))) }}</div>
                </div>
                <div class="header-actions">
                    <form method="GET" action="{{ url('shop-daily-item-report') }}" class="date-form">
                        <select name="shop" class="filter-input" onchange="this.form.submit()" style="border:none;outline:none;padding:7px 12px;font-size:13px;background:transparent;color:var(--text);min-width:160px;" required>
                            <option value="">-- Select Shop --</option>
                            @foreach($allShops as $shop)
                                <option value="{{ $shop['id'] }}" {{ $shopFilter == $shop['id'] ? 'selected' : '' }}>
                                    {{ $shop['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <input type="date" name="from_date" value="{{ $fromDate ?? date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                        <input type="date" name="to_date" value="{{ $toDate ?? date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                        <button type="submit">Filter</button>
                    </form>
                </div>
            </div>

            @if(empty($reportRows))
            <div class="panel">
                <div class="empty-state">
                    <h4>No data found</h4>
                    <p>There are no requested, received, or sold items from {{ date('F d, Y', strtotime($fromDate ?? date('Y-m-d'))) }} to {{ date('F d, Y', strtotime($toDate ?? date('Y-m-d'))) }}.</p>
                </div>
            </div>
            @else

            <div class="metrics" style="margin-bottom:1.25rem;">
                <div class="metric purple">
                    <div class="metric-label">Total Requested Qty</div>
                    <div class="metric-value">{{ number_format($grandTotals['requestedQty']) }}</div>
                    <div class="metric-sub">Tsh {{ number_format($grandTotals['requestValue'], 2) }}</div>
                </div>
                <div class="metric green">
                    <div class="metric-label">Total Received Qty</div>
                    <div class="metric-value">{{ number_format($grandTotals['receivedQty']) }}</div>
                    <div class="metric-sub">Tsh {{ number_format($grandTotals['receivedValue'], 2) }}</div>
                </div>
                <div class="metric amber">
                    <div class="metric-label">Total Sold Qty</div>
                    <div class="metric-value">{{ number_format($grandTotals['soldQty']) }}</div>
                    <div class="metric-sub">Tsh {{ number_format($grandTotals['soldValue'], 2) }}</div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Daily Item Summary — {{ date('F d, Y', strtotime($fromDate ?? date('Y-m-d'))) }} to {{ date('F d, Y', strtotime($toDate ?? date('Y-m-d'))) }}</span>
                </div>
                <div class="tbl-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Shop</th>
                                <th>Product</th>
                                <th>Req. Qty</th>
                                <th>Rec. Qty</th>
                                <th>Sold Qty</th>
                                <th>Δ Rec–Req</th>
                                <th>Δ Sold–Rec</th>
                                <th>Req. Value</th>
                                <th>Rec. Value</th>
                                <th>Sold Value</th>
                                <th>Discount</th>
                                <th>Offer</th>
                                <th>Δ Val</th>
                                <th>Mark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $index = 1; $prevShopId = null; $shopSubtotals = ['requestedQty'=>0,'receivedQty'=>0,'soldQty'=>0,'requestValue'=>0,'receivedValue'=>0,'soldValue'=>0,'discountValue'=>0,'offerValue'=>0]; @endphp
                            @foreach($reportRows as $row)
                                @php
                                    $shopId = $row['shopId'];
                                    $isNewShop = $prevShopId !== null && $shopId !== $prevShopId;
                                    if ($isNewShop) {
                                        echo '<tr class="shop-group"><td colspan="6" style="text-align:left;color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:0.05em;">Subtotal — ' . ($shopNameMap[$prevShopId] ?? 'Unknown') . '</td>';
                                        echo '<td class="num">' . number_format($shopSubtotals['receivedQty'] - $shopSubtotals['requestedQty']) . '</td>';
                                        echo '<td class="num">' . number_format($shopSubtotals['soldQty'] - $shopSubtotals['receivedQty']) . '</td>';
                                        echo '<td class="num" style="color:var(--purple);">Tsh ' . number_format($shopSubtotals['requestValue'], 2) . '</td>';
                                        echo '<td class="num" style="color:var(--green);">Tsh ' . number_format($shopSubtotals['receivedValue'], 2) . '</td>';
                                        echo '<td class="num" style="color:var(--amber);">Tsh ' . number_format($shopSubtotals['soldValue'], 2) . '</td>';
                                         echo '<td class="num">Tsh ' . number_format($shopSubtotals['discountValue'], 2) . '</td>';
                                         echo '<td class="num">Tsh ' . number_format($shopSubtotals['offerValue'], 2) . '</td>';
                                         $d = $shopSubtotals['receivedValue'] - $shopSubtotals['requestValue'] + $shopSubtotals['soldValue'] - $shopSubtotals['receivedValue'] - $shopSubtotals['discountValue'];
                                        echo '<td class="num" style="color:' . ($d >= 0 ? 'var(--green)' : 'var(--red)') . ';">Tsh ' . number_format($d, 2) . '</td>';
                                        echo '<td class="num"></td>';
                                        echo '</tr>';
                                         $shopSubtotals = ['requestedQty'=>0,'receivedQty'=>0,'soldQty'=>0,'requestValue'=>0,'receivedValue'=>0,'soldValue'=>0,'discountValue'=>0,'offerValue'=>0];
                                    }
                                    $prevShopId = $shopId;
                                    $shopSubtotals['requestedQty'] += $row['requestedQty'];
                                    $shopSubtotals['receivedQty'] += $row['receivedQty'];
                                    $shopSubtotals['soldQty'] += $row['soldQty'];
                                    $shopSubtotals['requestValue'] += $row['requestValue'];
                                    $shopSubtotals['receivedValue'] += $row['receivedValue'];
                                    $shopSubtotals['soldValue'] += $row['soldValue'];
                                    $shopSubtotals['discountValue'] += $row['discountValue'] ?? 0;
                                    $shopSubtotals['offerValue'] += $row['offerValue'] ?? 0;

                                    $diffRecReq = $row['diffRecReq'] ?? 0;
                                    $diffSoldRec = $row['diffSoldRec'] ?? 0;
                                    $diffValRecReq = $row['diffValRecReq'] ?? 0;
                                    $diffValSoldRec = $row['diffValSoldRec'] ?? 0;
                                    $discountVal = $row['discountValue'] ?? 0;
                                    $offerVal = $row['offerValue'] ?? 0;
                                    $netDiffVal = $diffValRecReq + $diffValSoldRec + $discountVal + $offerVal;

                                    $badges = '';
                                    foreach (($row['markStatus'] ?? []) as $status) {
                                        switch ($status) {
                                            case 'received_not_requested':
                                                $badges .= '<span class="badge badge-bad" style="margin:1px;">Rec w/o Req</span> ';
                                                break;
                                            case 'sold_without_request':
                                                $badges .= '<span class="badge badge-bad" style="margin:1px;">Sold w/o Req</span> ';
                                                break;
                                            case 'sold_without_receiving':
                                                $badges .= '<span class="badge badge-bad" style="margin:1px;">Sold w/o Rec</span> ';
                                                break;
                                            case 'over_sold':
                                                $badges .= '<span class="badge badge-over" style="margin:1px;">Over Sold</span> ';
                                                break;
                                            case 'over_received':
                                                $badges .= '<span class="badge badge-over" style="margin:1px;">Over Rec</span> ';
                                                break;
                                            case 'under_received':
                                                $badges .= '<span class="badge badge-over" style="margin:1px;">Under Rec</span> ';
                                                break;
                                            default:
                                                $badges .= '<span class="badge badge-ok" style="margin:1px;">Balanced</span> ';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="num">{{ $index++ }}</td>
                                    <td>{{ $shopNameMap[$row['shopId']] ?? 'Unknown' }}</td>
                                    <td>{{ $row['productName'] }}</td>
                                    <td class="num">{{ number_format($row['requestedQty']) }}</td>
                                    <td class="num">{{ number_format($row['receivedQty']) }}</td>
                                    <td class="num">{{ number_format($row['soldQty']) }}</td>
                                    <td class="num" style="color:{{ $diffRecReq >= 0 ? 'var(--green)' : 'var(--red)' }};">{{ $diffRecReq > 0 ? '+' : '' }}{{ number_format($diffRecReq) }}</td>
                                    <td class="num" style="color:{{ $diffSoldRec >= 0 ? 'var(--green)' : 'var(--red)' }};">{{ $diffSoldRec > 0 ? '+' : '' }}{{ number_format($diffSoldRec) }}</td>
                                    <td class="num" style="color:var(--purple);">Tsh {{ number_format($row['requestValue'], 2) }}</td>
                                    <td class="num" style="color:var(--green);">Tsh {{ number_format($row['receivedValue'], 2) }}</td>
                                    <td class="num" style="color:var(--amber);">Tsh {{ number_format($row['soldValue'], 2) }}</td>
                                    <td class="num">Tsh {{ number_format($discountVal, 2) }}</td>
                                    <td class="num" style="color:var(--purple);">Tsh {{ number_format($offerVal, 2) }}</td>
                                    <td class="num" style="color:{{ $netDiffVal >= 0 ? 'var(--green)' : 'var(--red)' }};">{{ $netDiffVal > 0 ? '+' : '' }}Tsh {{ number_format($netDiffVal, 2) }}</td>
                                    <td>{!! $badges !!}</td>
                                </tr>
                            @endforeach
                            @if($prevShopId !== null)
                                <tr class="shop-group">
                                    <td colspan="3" style="text-align:left;color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:0.05em;">Subtotal — {{ $shopNameMap[$prevShopId] ?? 'Unknown' }}</td>
                                    <td class="num">{{ number_format($shopSubtotals['requestedQty']) }}</td>
                                    <td class="num">{{ number_format($shopSubtotals['receivedQty']) }}</td>
                                    <td class="num">{{ number_format($shopSubtotals['soldQty']) }}</td>
                                    <td class="num">{{ number_format($shopSubtotals['receivedQty'] - $shopSubtotals['requestedQty']) }}</td>
                                    <td class="num">{{ number_format($shopSubtotals['soldQty'] - $shopSubtotals['receivedQty']) }}</td>
                                    <td class="num" style="color:var(--purple);">Tsh {{ number_format($shopSubtotals['requestValue'], 2) }}</td>
                                    <td class="num" style="color:var(--green);">Tsh {{ number_format($shopSubtotals['receivedValue'], 2) }}</td>
                                    <td class="num" style="color:var(--amber);">Tsh {{ number_format($shopSubtotals['soldValue'], 2) }}</td>
                                    <td class="num">Tsh {{ number_format($shopSubtotals['discountValue'], 2) }}</td>
                                    <td class="num" style="color:{{ ($shopSubtotals['receivedValue'] - $shopSubtotals['requestValue'] + $shopSubtotals['soldValue'] - $shopSubtotals['receivedValue'] - $shopSubtotals['discountValue']) >= 0 ? 'var(--green)' : 'var(--red)' }};">Tsh {{ number_format($shopSubtotals['receivedValue'] - $shopSubtotals['requestValue'] + $shopSubtotals['soldValue'] - $shopSubtotals['receivedValue'] + $shopSubtotals['discountValue'] + $offerVal, 2) }}</td>
                                    <td class="num"></td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">Grand Total</td>
                                <td class="num">{{ number_format($grandTotals['requestedQty']) }}</td>
                                <td class="num">{{ number_format($grandTotals['receivedQty']) }}</td>
                                <td class="num">{{ number_format($grandTotals['soldQty']) }}</td>
                                <td class="num">{{ number_format($grandTotals['receivedQty'] - $grandTotals['requestedQty']) }}</td>
                                <td class="num">{{ number_format($grandTotals['soldQty'] - $grandTotals['receivedQty']) }}</td>
                                <td class="num" style="color:var(--purple);">Tsh {{ number_format($grandTotals['requestValue'], 2) }}</td>
                                <td class="num" style="color:var(--green);">Tsh {{ number_format($grandTotals['receivedValue'], 2) }}</td>
                                <td class="num" style="color:var(--amber);">Tsh {{ number_format($grandTotals['soldValue'], 2) }}</td>
                                <td class="num">Tsh {{ number_format($grandTotals['discountValue'], 2) }}</td>
                                <td class="num" style="color:{{ ($grandTotals['receivedValue'] - $grandTotals['requestValue'] + $grandTotals['soldValue'] - $grandTotals['receivedValue'] - $grandTotals['discountValue']) >= 0 ? 'var(--green)' : 'var(--red)' }};">Tsh {{ number_format($grandTotals['receivedValue'] - $grandTotals['requestValue'] + $grandTotals['soldValue'] - $grandTotals['receivedValue'] + $grandTotals['discountValue'] + $offerVal, 2) }}</td>
                                <td class="num"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </main>
</body>
</html>
