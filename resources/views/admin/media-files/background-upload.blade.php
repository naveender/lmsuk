<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Background Video Uploader</title>
    <!-- Include basic bootstrap and feather icons -->
    <link rel="stylesheet" href="{{ asset('theme/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.1/feather.min.css">
    <style>
        body {
            background-color: #f8f8f8;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', Helvetica, Arial, sans-serif;
        }
        .uploader-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px 0 rgba(0,0,0,0.1);
            background: #fff;
            width: 90%;
            max-width: 500px;
            padding: 30px;
        }
        .progress {
            height: 12px;
            border-radius: 6px;
        }
        .spin {
            animation: rotate 2s linear infinite;
        }
        @keyframes rotate {
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="uploader-card">
    <div class="text-center mb-2">
        <i class="feather icon-upload-cloud text-primary spin" style="font-size: 3.5rem; display: inline-block;"></i>
        <h4 class="mt-2 font-weight-bold" id="lblTitle">Background Uploading</h4>
        <p class="text-muted small" id="lblFilename">Preparing upload files...</p>
    </div>

    <!-- Progress UI -->
    <div class="progress mb-1">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" id="progressBarValue" role="progressbar" style="width: 0%"></div>
    </div>

    <div class="d-flex justify-content-between mb-2 small text-muted font-weight-bold">
        <span id="lblPercentage">0%</span>
        <span id="lblSize">0 MB / 0 MB</span>
    </div>

    <div class="card bg-light p-2 mb-2">
        <div class="d-flex justify-content-between small text-muted">
            <span>Upload Speed:</span>
            <span class="font-weight-bold" id="lblSpeed">0 KB/s</span>
        </div>
        <div class="d-flex justify-content-between small text-muted mt-50">
            <span>Time Remaining:</span>
            <span class="font-weight-bold" id="lblEta">ETA: --</span>
        </div>
        <div class="d-flex justify-content-between small text-muted mt-50">
            <span>Status:</span>
            <span class="badge badge-warning" id="lblStatus">Processing Chunks</span>
        </div>
    </div>

    <div class="text-center">
        <button class="btn btn-outline-danger btn-sm font-weight-bold" id="btnCancel">
            <i class="feather icon-x-circle"></i> Cancel & Close
        </button>
    </div>
</div>

<script src="{{ asset('theme/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let file = null;
        let config = null;

        // 1. Fetch file reference and configs from the parent opener window
        if (window.opener && !window.opener.closed) {
            file = window.opener.parentSelectedFile;
            config = window.opener.parentUploadConfig;
        }

        if (!file || !config) {
            alert('Upload source file references not found. Please start upload from the main panel page.');
            window.close();
            return;
        }

        document.getElementById('lblFilename').textContent = file.name;
        document.getElementById('lblTitle').textContent = `Uploading: ${config.title}`;

        const CHUNK_SIZE = config.chunk_size;
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        const uploadId = 'up_' + btoa(unescape(encodeURIComponent(`${file.name}_${file.size}`))).replace(/=/g, '').substr(0, 20);

        let completedChunks = [];
        let startTime = Date.now();
        let isCancelled = false;

        function roundSize(bytes) {
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        function updateProgressUI(loadedBytes) {
            const percentage = Math.round((loadedBytes / file.size) * 100);
            
            document.getElementById('progressBarValue').style.width = `${percentage}%`;
            document.getElementById('lblPercentage').textContent = `${percentage}%`;
            document.getElementById('lblSize').textContent = `${roundSize(loadedBytes)} / ${roundSize(file.size)}`;

            const elapsedSeconds = (Date.now() - startTime) / 1000;
            if (elapsedSeconds > 0) {
                const speedBytesPerSec = loadedBytes / elapsedSeconds;
                const speedKbps = speedBytesPerSec / 1024;
                if (speedKbps > 1024) {
                    document.getElementById('lblSpeed').textContent = `${(speedKbps / 1024).toFixed(2)} MB/s`;
                } else {
                    document.getElementById('lblSpeed').textContent = `${speedKbps.toFixed(2)} KB/s`;
                }

                const remainingBytes = file.size - loadedBytes;
                const etaSeconds = remainingBytes / speedBytesPerSec;
                if (etaSeconds > 60) {
                    document.getElementById('lblEta').textContent = `${Math.floor(etaSeconds / 60)}m ${Math.round(etaSeconds % 60)}s`;
                } else {
                    document.getElementById('lblEta').textContent = `${Math.round(etaSeconds)}s`;
                }
            }
        }

        // Cancel button
        document.getElementById('btnCancel').addEventListener('click', () => {
            isCancelled = true;
            window.close();
        });

        // Get status of previously uploaded chunks and begin
        axios.get(`/admin/media-files/upload-status?upload_id=${uploadId}`)
            .then(response => {
                completedChunks = response.data.completed_chunks || [];
                uploadChunkLoop(0);
            })
            .catch(() => {
                uploadChunkLoop(0);
            });

        function uploadChunkLoop(index) {
            if (isCancelled) return;

            if (index >= totalChunks) {
                return;
            }

            if (completedChunks.includes(index)) {
                const completedBytes = Math.min((index + 1) * CHUNK_SIZE, file.size);
                updateProgressUI(completedBytes);
                uploadChunkLoop(index + 1);
                return;
            }

            const start = index * CHUNK_SIZE;
            const end = Math.min(start + CHUNK_SIZE, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('chunk_index', index);
            formData.append('total_chunks', totalChunks);
            formData.append('filename', file.name);
            formData.append('upload_id', uploadId);
            formData.append('storage_target', config.storage_target);
            formData.append('title', config.title);
            formData.append('description', config.description);

            // Append metadata parameters
            if (window.opener && !window.opener.closed && window.opener.parentMetadataConfig) {
                const meta = window.opener.parentMetadataConfig;
                formData.append('subject_id', meta.subject_id);
                formData.append('class_id', meta.class_id);
                formData.append('year_group_id', meta.year_group_id);
                formData.append('academic_year', meta.academic_year);
                formData.append('duration', meta.duration);
                formData.append('thumbnail_path', meta.thumbnail_path);
                formData.append('publication_status', meta.publication_status);

                if (meta.course_assigned) {
                    formData.append('course_id', meta.course_id);
                    formData.append('week_mode', meta.week_mode);
                    formData.append('selected_week_id', meta.selected_week_id);
                    formData.append('new_week_name', meta.new_week_name);
                    formData.append('new_week_due_date', meta.new_week_due_date);
                    formData.append('create_new_course', meta.create_new_course);
                    formData.append('new_course_name', meta.new_course_name);
                }
            }

            axios.post('/admin/media-files/upload-chunk', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    const loadedInChunk = progressEvent.loaded;
                    const totalLoadedBytes = start + loadedInChunk;
                    updateProgressUI(totalLoadedBytes);
                }
            })
            .then(response => {
                if (response.data.status === 'completed') {
                    document.getElementById('lblStatus').textContent = 'Completed!';
                    document.getElementById('lblStatus').className = 'badge badge-success';
                    document.getElementById('progressBarValue').style.width = '100%';
                    document.getElementById('lblPercentage').textContent = '100%';
                    
                    // Alert completion
                    alert('Video uploaded successfully in the background!');
                    
                    // Notify parent if it is open
                    if (window.opener && !window.opener.closed) {
                        try {
                            window.opener.location.reload();
                        } catch(e) {}
                    }
                    window.close();
                } else {
                    completedChunks.push(index);
                    uploadChunkLoop(index + 1);
                }
            })
            .catch(error => {
                console.error(error);
                document.getElementById('lblStatus').textContent = 'Failed';
                document.getElementById('lblStatus').className = 'badge badge-danger';
                alert('Background upload interrupted: ' + (error.message || 'Server error'));
            });
        }
    });
</script>

</body>
</html>
