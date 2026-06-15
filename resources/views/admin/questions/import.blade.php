@extends('layouts.app')

@section('title', 'Import Questions - Admin Panel')

@push('styles')
<style>
    /* Premium modern gradients and styling */
    .import-card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: none;
        transition: all 0.3s ease;
        background: #ffffff;
    }
    
    .dark-layout .import-card {
        background: #1a233a;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }
    
    .dropzone-container {
        border: 2px dashed #4b6bfb;
        border-radius: 12px;
        background: rgba(75, 107, 251, 0.02);
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .dropzone-container:hover, .dropzone-container.dragover {
        background: rgba(75, 107, 251, 0.08);
        border-color: #2b4cff;
        transform: translateY(-2px);
    }

    .dropzone-icon {
        font-size: 3.5rem;
        color: #4b6bfb;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .dropzone-container:hover .dropzone-icon {
        transform: scale(1.1) translateY(-5px);
        color: #2b4cff;
    }

    /* Glowing Progress Bar */
    .progress-wrapper {
        position: relative;
        margin: 25px 0;
    }

    .progress-bar-container {
        height: 18px;
        width: 100%;
        background-color: #f1f3f9;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        position: relative;
    }

    .dark-layout .progress-bar-container {
        background-color: #101626;
    }

    .progress-bar-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #4b6bfb 0%, #8b5cf6 50%, #d946ef 100%);
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite, progress-stripes 1s linear infinite;
        border-radius: 20px;
        transition: width 0.4s cubic-bezier(0.1, 0.8, 0.1, 1);
        box-shadow: 0 0 10px rgba(75, 107, 251, 0.5);
    }

    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Pulse effects */
    .pulse-glow {
        animation: glow-pulse 1.8s infinite ease-in-out;
    }

    @keyframes glow-pulse {
        0% { box-shadow: 0 0 0 0 rgba(75, 107, 251, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(75, 107, 251, 0); }
        100% { box-shadow: 0 0 0 0 rgba(75, 107, 251, 0); }
    }

    /* Log card styling */
    .log-container {
        max-height: 350px;
        overflow-y: auto;
        border-radius: 8px;
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 0.9rem;
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
    }

    .dark-layout .log-container {
        background: #0d121f;
        border-color: #24304f;
    }

    .log-row {
        padding: 8px 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: flex-start;
    }

    .dark-layout .log-row {
        border-bottom-color: rgba(255,255,255,0.05);
    }

    .log-row:last-child {
        border-bottom: none;
    }

    .log-badge {
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: bold;
        margin-right: 12px;
        text-transform: uppercase;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .log-error {
        background: rgba(234, 84, 85, 0.1);
        color: #ea5455;
    }

    .log-warning {
        background: rgba(255, 159, 67, 0.1);
        color: #ff9f43;
    }

    .info-badge {
        font-size: 0.8rem;
        padding: 3px 8px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">Import Questions</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
                                <li class="breadcrumb-item active">Import</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            <!-- Row for top level components -->
            <div class="row">
                <!-- Left panel: Guide & Upload -->
                <div class="col-lg-7 col-md-12">
                    <!-- Instruction Card -->
                    <div class="card import-card mb-2">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="card-title font-weight-bold">How to Import Questions</h4>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted">
                                Follow these quick steps to upload and import questions in bulk. Supported formats: <strong>CSV</strong>.
                            </p>
                            
                            <div class="d-flex align-items-start mb-1">
                                <div class="bg-light-primary rounded p-1 mr-1 text-center" style="width: 40px; height: 40px; line-height: 20px;">
                                    <span class="font-weight-bold text-primary">1</span>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold mb-0">Download the Template</h5>
                                    <p class="text-muted">Always use our standardized CSV file to structure your question columns correctly.</p>
                                    <a href="{{ route('admin.questions.import-sample') }}" class="btn btn-outline-primary btn-sm mb-1">
                                        <i class="feather icon-download mr-50"></i> Download Sample CSV Template
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-1">
                                <div class="bg-light-primary rounded p-1 mr-1 text-center" style="width: 40px; height: 40px; line-height: 20px;">
                                    <span class="font-weight-bold text-primary">2</span>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold mb-0">Prepare Your Question Data</h5>
                                    <p class="text-muted mb-0">Define fields like <strong>title</strong>, <strong>type</strong>, <strong>subject</strong>, and options. Missing subjects or topics will be automatically created on the fly!</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="bg-light-primary rounded p-1 mr-1 text-center" style="width: 40px; height: 40px; line-height: 20px;">
                                    <span class="font-weight-bold text-primary">3</span>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold mb-0">Upload and Process</h5>
                                    <p class="text-muted mb-0">Drag and drop your final CSV. The parser handles your data in small safe chunks to avoid server limits or timeouts.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Dropzone Card -->
                    <div class="card import-card" id="upload-card">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold mb-2">Upload File</h4>
                            
                            <div class="dropzone-container" id="dropzone">
                                <input type="file" id="csv-file-input" accept=".csv" class="d-none">
                                <div class="dropzone-icon">
                                    <i class="feather icon-upload-cloud"></i>
                                </div>
                                <h5 class="font-weight-bold">Drag and drop your CSV file here</h5>
                                <p class="text-muted">or click to browse from your computer</p>
                                <div class="badge badge-light-secondary mt-1">Maximum file size: 10MB</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right panel: Progress & Stats -->
                <div class="col-lg-5 col-md-12">
                    <!-- Progress Card (Hidden by default, shown during parse/import) -->
                    <div class="card import-card d-none" id="progress-card">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="card-title font-weight-bold">Import Status</h4>
                            <span class="badge badge-light-primary pulse-glow" id="status-badge">Ready</span>
                        </div>
                        
                        <div class="card-body">
                            <div id="file-info" class="p-1 mb-2 rounded bg-light d-flex align-items-center">
                                <i class="feather icon-file text-primary font-large-1 mr-1"></i>
                                <div class="overflow-hidden">
                                    <h6 class="font-weight-bold mb-0 text-truncate" id="import-file-name">questions.csv</h6>
                                    <small class="text-muted" id="import-file-size">0 KB</small>
                                </div>
                            </div>

                            <div class="progress-wrapper">
                                <div class="d-flex justify-content-between mb-50">
                                    <span class="font-weight-bold">Overall Progress</span>
                                    <span class="font-weight-bold text-primary" id="progress-percent">0%</span>
                                </div>
                                <div class="progress-bar-container">
                                    <div class="progress-bar-fill" id="progress-bar"></div>
                                </div>
                            </div>

                            <!-- Metrics Grid -->
                            <div class="row text-center mt-2">
                                <div class="col-4 border-right">
                                    <h4 class="font-weight-bold text-dark mb-0" id="stat-total">0</h4>
                                    <small class="text-muted">Total Questions</small>
                                </div>
                                <div class="col-4 border-right">
                                    <h4 class="font-weight-bold text-success mb-0" id="stat-success">0</h4>
                                    <small class="text-muted">Imported</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="font-weight-bold text-danger mb-0" id="stat-failed">0</h4>
                                    <small class="text-muted">Failed</small>
                                </div>
                            </div>

                            <!-- Controls -->
                            <div class="mt-3 text-center">
                                <button class="btn btn-primary btn-block btn-lg" id="btn-start-import">
                                    <i class="feather icon-play mr-50"></i> Start Import
                                </button>
                                <button class="btn btn-warning btn-block btn-lg d-none" id="btn-retry-import">
                                    <i class="feather icon-refresh-cw mr-50"></i> Retry Remaining
                                </button>
                                <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-secondary btn-block mt-1 d-none" id="btn-view-questions">
                                    <i class="feather icon-list mr-50"></i> Go to Directory
                                </a>
                                <button class="btn btn-outline-danger btn-block mt-1" id="btn-cancel">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Column Guide Card (Static Reference helper) -->
                    <div class="card import-card" id="guide-card">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4 class="card-title font-weight-bold">Supported Types Reference</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center pl-0 pr-0">
                                    <span>Single Choice (Radio/Dropdown)</span>
                                    <span class="badge badge-light-primary info-badge">single_choice_radio</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center pl-0 pr-0">
                                    <span>Multiple Choice MCQ</span>
                                    <span class="badge badge-light-primary info-badge">multiple_choice</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center pl-0 pr-0">
                                    <span>Fill in the Blanks</span>
                                    <span class="badge badge-light-primary info-badge">fill_in_the_blanks</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center pl-0 pr-0">
                                    <span>Matching Text / Drag Drop</span>
                                    <span class="badge badge-light-primary info-badge">matching_text</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center pl-0 pr-0">
                                    <span>Free Essay Text</span>
                                    <span class="badge badge-light-primary info-badge">free_text</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center pl-0 pr-0 border-bottom-0">
                                    <span>File Upload Assessment</span>
                                    <span class="badge badge-light-primary info-badge">file_upload</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row for error/warning log console (Shown only when failures/warnings occur) -->
            <div class="row mt-2 d-none" id="log-card-row">
                <div class="col-12">
                    <div class="card import-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title font-weight-bold text-danger">
                                <i class="feather icon-alert-circle mr-50"></i> Import Logs & Errors
                            </h4>
                            <span class="badge badge-danger" id="log-errors-count">0 Errors</span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">If some questions fail, check their exact row numbers below to fix and re-import them. Other valid questions will still be imported successfully.</p>
                            <div class="log-container" id="log-container">
                                <!-- Log rows dynamically injected -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('csv-file-input');
        const uploadCard = document.getElementById('upload-card');
        const progressCard = document.getElementById('progress-card');
        const guideCard = document.getElementById('guide-card');
        const logCardRow = document.getElementById('log-card-row');
        
        // Progress elements
        const statusBadge = document.getElementById('status-badge');
        const importFileName = document.getElementById('import-file-name');
        const importFileSize = document.getElementById('import-file-size');
        const progressPercent = document.getElementById('progress-percent');
        const progressBar = document.getElementById('progress-bar');
        
        // Stats elements
        const statTotal = document.getElementById('stat-total');
        const statSuccess = document.getElementById('stat-success');
        const statFailed = document.getElementById('stat-failed');
        
        // Log elements
        const logContainer = document.getElementById('log-container');
        const logErrorsCount = document.getElementById('log-errors-count');
        
        // Buttons
        const btnStartImport = document.getElementById('btn-start-import');
        const btnRetryImport = document.getElementById('btn-retry-import');
        const btnViewQuestions = document.getElementById('btn-view-questions');
        const btnCancel = document.getElementById('btn-cancel');

        let selectedFile = null;
        let importToken = null;
        let totalRows = 0;
        let currentOffset = 0;
        const chunkSize = 10; // Process 10 rows at a time
        let successCount = 0;
        let failedCount = 0;
        let isImporting = false;
        let errorsList = [];

        // Click trigger file browser
        dropzone.addEventListener('click', () => fileInput.click());

        // Drag events
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            }, false);
        });

        // Drop file
        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                handleFileSelect(files[0]);
            }
        });

        // Select file input
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleFileSelect(e.target.files[0]);
            }
        });

        // Cancel reset
        btnCancel.addEventListener('click', () => {
            if (isImporting && !confirm('Are you sure you want to stop the current import?')) {
                return;
            }
            resetUI();
        });

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Parse file to get row count
        function handleFileSelect(file) {
            if (!file.name.endsWith('.csv') && !file.name.endsWith('.txt')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File',
                    text: 'Please upload a valid CSV file.',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
                return;
            }

            selectedFile = file;
            importFileName.textContent = file.name;
            importFileSize.textContent = formatBytes(file.size);

            // Upload to server for verification and token generation
            Swal.fire({
                title: 'Parsing File...',
                text: 'Analyzing columns and rows',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('file', selectedFile);

            axios.post('{{ route("admin.questions.import-parse") }}', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                Swal.close();
                if (response.data.success) {
                    importToken = response.data.import_token;
                    totalRows = response.data.total_rows;
                    
                    // Show progress card, hide upload/guide cards
                    uploadCard.classList.add('d-none');
                    guideCard.classList.add('d-none');
                    progressCard.classList.remove('d-none');
                    
                    // Reset stats
                    statTotal.textContent = totalRows;
                    statSuccess.textContent = '0';
                    statFailed.textContent = '0';
                    progressPercent.textContent = '0%';
                    progressBar.style.width = '0%';
                    
                    statusBadge.textContent = 'Ready';
                    statusBadge.className = 'badge badge-light-primary';
                    
                    btnStartImport.classList.remove('d-none');
                    btnRetryImport.classList.add('d-none');
                    btnViewQuestions.classList.add('d-none');
                    btnCancel.classList.remove('d-none');

                    // If file has 0 question rows
                    if (totalRows === 0) {
                        btnStartImport.classList.add('disabled');
                        btnStartImport.disabled = true;
                        statusBadge.textContent = 'Empty File';
                        statusBadge.className = 'badge badge-light-warning';
                    } else {
                        btnStartImport.classList.remove('disabled');
                        btnStartImport.disabled = false;
                    }
                }
            })
            .catch(error => {
                Swal.close();
                let errMsg = 'Failed to parse the CSV file.';
                if (error.response && error.response.data && error.response.data.message) {
                    errMsg = error.response.data.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Parsing Error',
                    text: errMsg,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            });
        }

        // Start import execution
        btnStartImport.addEventListener('click', () => {
            startImportProcess();
        });

        btnRetryImport.addEventListener('click', () => {
            startImportProcess();
        });

        function startImportProcess() {
            isImporting = true;
            btnStartImport.classList.add('d-none');
            btnRetryImport.classList.add('d-none');
            btnCancel.classList.add('d-none');
            
            statusBadge.textContent = 'Processing';
            statusBadge.className = 'badge badge-light-warning pulse-glow';
            
            importNextChunk();
        }

        function importNextChunk() {
            if (!isImporting) return;

            statusBadge.textContent = `Importing rows ${currentOffset + 1} to ${Math.min(currentOffset + chunkSize, totalRows)}...`;

            axios.post('{{ route("admin.questions.import-process") }}', {
                import_token: importToken,
                offset: currentOffset,
                limit: chunkSize
            })
            .then(response => {
                if (response.data.success) {
                    const results = response.data.results;
                    
                    successCount += results.success_count;
                    failedCount += results.failed_count;
                    
                    // Update stats
                    statSuccess.textContent = successCount;
                    statFailed.textContent = failedCount;
                    
                    // Update logs if there are warnings or errors
                    if (results.errors.length > 0 || results.warnings.length > 0) {
                        logCardRow.classList.remove('d-none');
                        
                        results.errors.forEach(err => {
                            errorsList.push(err);
                            const rowEl = document.createElement('div');
                            rowEl.className = 'log-row';
                            rowEl.innerHTML = `
                                <span class="log-badge log-error">Row ${err.row}</span>
                                <span class="text-dark font-weight-bold">${err.message}</span>
                            `;
                            logContainer.appendChild(rowEl);
                        });

                        results.warnings.forEach(warn => {
                            const rowEl = document.createElement('div');
                            rowEl.className = 'log-row';
                            rowEl.innerHTML = `
                                <span class="log-badge log-warning">Row ${warn.row}</span>
                                <span class="text-muted">${warn.message}</span>
                            `;
                            logContainer.appendChild(rowEl);
                        });

                        // Scroll log to bottom
                        logContainer.scrollTop = logContainer.scrollHeight;
                        logErrorsCount.textContent = `${errorsList.length} Errors`;
                    }

                    // Update Progress bar
                    currentOffset += results.processed;
                    let percent = Math.min(Math.round((currentOffset / totalRows) * 100), 100);
                    progressPercent.textContent = `${percent}%`;
                    progressBar.style.width = `${percent}%`;

                    // Check if complete
                    if (results.completed || currentOffset >= totalRows) {
                        finishImport();
                    } else {
                        // Recursively call next chunk
                        importNextChunk();
                    }
                }
            })
            .catch(error => {
                isImporting = false;
                statusBadge.textContent = 'Paused / Error';
                statusBadge.className = 'badge badge-light-danger';
                btnRetryImport.classList.remove('d-none');
                btnCancel.classList.remove('d-none');

                let errMsg = 'Connection lost or server timeout occurred during chunk processing.';
                if (error.response && error.response.data && error.response.data.message) {
                    errMsg = error.response.data.message;
                }
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Import Interrupted',
                    text: errMsg + ' You can retry importing the remaining questions.',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
            });
        }

        function finishImport() {
            isImporting = false;
            statusBadge.textContent = 'Completed';
            statusBadge.className = 'badge badge-light-success';
            
            btnViewQuestions.classList.remove('d-none');
            btnCancel.textContent = 'Import Another File';
            btnCancel.classList.remove('d-none');
            btnCancel.className = 'btn btn-outline-primary btn-block mt-1';

            let titleStr = 'Import Completed!';
            let textStr = `Successfully imported ${successCount} questions.`;
            let iconType = 'success';

            if (failedCount > 0) {
                titleStr = 'Import Complete with Failures';
                textStr = `Imported ${successCount} questions successfully. ${failedCount} questions failed to import. See details in the log.`;
                iconType = 'warning';
            }

            Swal.fire({
                icon: iconType,
                title: titleStr,
                text: textStr,
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }

        function resetUI() {
            selectedFile = null;
            importToken = null;
            totalRows = 0;
            currentOffset = 0;
            successCount = 0;
            failedCount = 0;
            isImporting = false;
            errorsList = [];

            // UI states reset
            uploadCard.classList.remove('d-none');
            guideCard.classList.remove('d-none');
            progressCard.classList.add('d-none');
            logCardRow.classList.add('d-none');
            
            fileInput.value = '';
            logContainer.innerHTML = '';
            logErrorsCount.textContent = '0 Errors';

            btnCancel.textContent = 'Cancel';
            btnCancel.className = 'btn btn-outline-danger btn-block mt-1';
        }
    });
</script>
@endpush
