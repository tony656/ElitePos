@include('sidenav')

<div class="main-content">
<div class="container-fluid px-4">
    {{-- Debug info --}}
    @if(app()->isLocal())
        <div class="alert alert-info mb-3">
            <strong>Debug:</strong>
            <br>tablesWithAccount count: {{ count($tablesWithAccount) ?? 'undefined' }}
            <br>accountMap count: {{ count($accountMap ?? []) }}
            <br>tables: {{ implode(', ', array_column($tablesWithAccount ?? [], 'name')) }}
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Account Migration Tool</h1>
            <p class="text-muted mb-0">Replace account names with account IDs in database tables</p>
        </div>
        <div>
            <button class="btn btn-danger" onclick="migrateAll()">
                <i class="bi bi-arrow-repeat"></i> Migrate All Tables
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-database me-2"></i>Tables with Account Column
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Purpose:</strong> This tool replaces account names (strings) with account IDs (integers) in all tables that have an 'account' or 'account_id' column.
                        This is needed after changing the session to store account IDs instead of names.
                    </div>

                    @if(empty($tablesWithAccount))
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>No tables found with account columns.</strong><br>
                            This could mean:
                            <ul class="mb-0 mt-2">
                                <li>All tables already have numeric account IDs (migration complete)</li>
                                <li>The database tables use a different column name</li>
                                <li>There are no tables with account columns in the database</li>
                            </ul>
                            <hr>
                            <small class="text-muted">
                                Database: {{ config('database.connections.mysql.database') }}<br>
                                Tables found: {{ count($tablesWithAccount) }}
                            </small>
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="migrationTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Table Name</th>
                                    <th width="15%">Needs/Total</th>
                                    <th width="30%">Sample Values</th>
                                    <th width="20%">Actions</th>
                                    <th width="5%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tablesWithAccount as $index => $table)
                                <tr id="row-{{ $index }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <code>{{ $table['name'] }}</code>
                                        <br><small class="text-muted">Column: {{ $table['column'] }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $table['needs_migration'] > 0 ? 'bg-warning text-dark' : 'bg-success' }}" id="count-{{ $index }}">
                                            {{ $table['needs_migration'] }} / {{ $table['total_records'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small" id="sample-{{ $index }}">
                                            @foreach($table['sample'] as $sample)
                                                <span class="badge bg-secondary me-1 mb-1">{{ $sample }}</span>
                                            @endforeach
                                            @if(count($table['sample']) == 0)
                                                <em class="text-muted">No sample</em>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="migrateTable('{{ $table['name'] }}', {{ $index }})">
                                            <i class="bi bi-arrow-right-circle"></i> Migrate
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="dryRun('{{ $table['name'] }}', {{ $index }})">
                                            Dry Run
                                        </button>
                                    </td>
                                    <td>
                                        <span class="status-badge" id="status-{{ $index }}">
                                            <i class="bi bi-clock text-muted"></i>
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-check-circle display-4 text-success"></i>
                                        <p class="mt-2">All tables have been migrated! No account names found.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Account Mapping Reference -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-list-ul me-2"></i>Account Name → ID Mapping
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($accountMap as $name => $id)
                        <div class="col-md-3 col-sm-4 col-6 mb-2">
                            <div class="border rounded p-2">
                                <strong>{{ $name }}</strong>
                                <div class="text-muted small">ID: {{ $id }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <div>Migrating table...</div>
                <small class="text-muted" id="loadingTableName"></small>
            </div>
        </div>
    </div>
</div>

<script>
let currentTableIndex = null;

function dryRun(tableName, index) {
    currentTableIndex = index;
    showLoading('Dry running ' + tableName + '...');
    
    fetch("{{ route('migration.dry-run') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ table: tableName })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            if (data.dry_run) {
                alert('DRY RUN:\n\nWould update ' + data.would_update + ' account records.\n\nMappings:\n' + 
                    Object.entries(data.mappings).map(([name, id]) => `${name} → ${id}`).join('\n'));
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        alert('Request failed: ' + error.message);
    });
}

function migrateTable(tableName, index) {
    if (!confirm('Are you sure you want to migrate table "' + tableName + '"?\n\nThis will replace account names with account IDs.')) {
        return;
    }
    
    currentTableIndex = index;
    showLoading('Migrating ' + tableName + '...');
    
    fetch("{{ route('migration.table') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ table: tableName })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            const statusEl = document.getElementById('status-' + index);
            statusEl.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
            
            const countEl = document.getElementById('count-' + index);
            countEl.textContent = '0';
            countEl.classList.remove('bg-warning');
            countEl.classList.add('bg-success');
            
            alert('Success! ' + data.message);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        alert('Request failed: ' + error.message);
    });
}

function migrateAll() {
    if (!confirm('Are you sure you want to migrate ALL tables at once?\n\nThis will process every table with account columns.')) {
        return;
    }
    
    if (!confirm('Second confirmation: This will update the entire database. Continue?')) {
        return;
    }
    
    showLoading('Migrating all tables...');
    
    fetch("{{ route('migration.all') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            alert('Migration complete!\n\nTotal records updated: ' + data.total_updated + '\n\nResults:\n' + 
                Object.entries(data.results).map(([table, info]) => 
                    `${table}: ${info.updated || 'skipped'}`
                ).join('\n'));
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        hideLoading();
        alert('Request failed: ' + error.message);
    });
}

function showLoading(message) {
    document.getElementById('loadingTableName').textContent = message;
    const modal = new bootstrap.Modal(document.getElementById('loadingModal'));
    modal.show();
}

function hideLoading() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
    if (modal) modal.hide();
}
</script>
</div>
</body>
</html>