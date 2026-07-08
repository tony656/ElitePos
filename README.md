<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload to GitHub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #0B1E3D;
            --navy-light: #1A3A6B;
            --amber: #F59E0B;
            --emerald: #059669;
            --rose: #E11D48;
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
            --shadow: 0 4px 20px rgba(11,30,61,.08);
            --shadow-lg: 0 10px 40px rgba(11,30,61,.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--slate-50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .upload-container {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            max-width: 600px;
            width: 100%;
            padding: 2.5rem;
            transition: transform 0.3s ease;
        }

        .upload-container:hover {
            transform: translateY(-4px);
        }

        .upload-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .upload-header .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            border-radius: 50%;
            color: var(--white);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .upload-header h2 {
            color: var(--navy);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .upload-header p {
            color: var(--slate-500);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--slate-700);
            margin-bottom: 0.4rem;
        }

        .form-label .required {
            color: var(--rose);
            margin-left: 2px;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            font-size: 0.95rem;
            color: var(--slate-800);
            background: var(--white);
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--amber);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .form-control::placeholder {
            color: var(--slate-400);
        }

        .form-control.file-input {
            padding: 0.5rem;
            cursor: pointer;
        }

        .form-control.file-input::-webkit-file-upload-button {
            padding: 0.5rem 1.25rem;
            border: none;
            background: var(--navy);
            color: var(--white);
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-right: 1rem;
        }

        .form-control.file-input::-webkit-file-upload-button:hover {
            background: var(--navy-light);
        }

        .form-control.file-input::file-selector-button {
            padding: 0.5rem 1.25rem;
            border: none;
            background: var(--navy);
            color: var(--white);
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-right: 1rem;
        }

        .form-control.file-input::file-selector-button:hover {
            background: var(--navy-light);
        }

        .form-hint {
            font-size: 0.8rem;
            color: var(--slate-400);
            margin-top: 0.3rem;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: var(--slate-50);
            border-radius: 6px;
            margin-top: 0.5rem;
            border: 1px solid var(--slate-200);
            font-size: 0.85rem;
            color: var(--slate-600);
        }

        .file-info i {
            color: var(--emerald);
            font-size: 1.1rem;
        }

        .file-info .file-name {
            font-weight: 600;
            color: var(--slate-800);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--amber);
            cursor: pointer;
            flex-shrink: 0;
        }

        .checkbox-group label {
            color: var(--slate-600);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .checkbox-group label a {
            color: var(--navy);
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-group label a:hover {
            text-decoration: underline;
            color: var(--amber);
        }

        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, var(--amber) 0%, #d97706 100%);
            border: none;
            border-radius: 8px;
            color: var(--navy);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-submit:active {
            transform: scale(0.97);
        }

        .btn-submit i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }

        .btn-submit:hover i {
            transform: translateX(4px);
        }

        .progress-container {
            display: none;
            margin-top: 1.5rem;
        }

        .progress-container.active {
            display: block;
        }

        .progress-bar-wrapper {
            width: 100%;
            height: 8px;
            background: var(--slate-200);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--amber), #d97706);
            border-radius: 4px;
            transition: width 0.3s ease;
            width: 0%;
        }

        .progress-text {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--slate-500);
            margin-top: 0.3rem;
        }

        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            display: none;
        }

        .alert.active {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: var(--emerald);
            color: var(--white);
        }

        .alert-error {
            background: var(--rose);
            color: var(--white);
        }

        .alert-info {
            background: var(--navy);
            color: var(--white);
        }

        .alert .alert-icon {
            font-size: 1.2rem;
        }

        .upload-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--slate-200);
        }

        .upload-footer p {
            color: var(--slate-400);
            font-size: 0.8rem;
        }

        .upload-footer a {
            color: var(--navy);
            text-decoration: none;
            font-weight: 600;
        }

        .upload-footer a:hover {
            color: var(--amber);
        }

        /* Responsive */
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }

            .upload-container {
                padding: 1.5rem;
            }

            .upload-header .icon {
                width: 56px;
                height: 56px;
                font-size: 1.5rem;
            }

            .upload-header h2 {
                font-size: 1.25rem;
            }

            .form-control.file-input::-webkit-file-upload-button {
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <div class="upload-container">
        <!-- Header -->
        <div class="upload-header">
            <div class="icon">
                <i class="bi bi-github"></i>
            </div>
            <h2>Upload to GitHub</h2>
            <p>Upload your files directly to your GitHub repository</p>
        </div>

        <!-- Alert Messages -->
        <div id="alertSuccess" class="alert alert-success">
            <span class="alert-icon"><i class="bi bi-check-circle-fill"></i></span>
            <span id="successMessage">File uploaded successfully!</span>
        </div>

        <div id="alertError" class="alert alert-error">
            <span class="alert-icon"><i class="bi bi-exclamation-circle-fill"></i></span>
            <span id="errorMessage">Upload failed. Please try again.</span>
        </div>

        <div id="alertInfo" class="alert alert-info">
            <span class="alert-icon"><i class="bi bi-info-circle-fill"></i></span>
            <span id="infoMessage">Uploading your file...</span>
        </div>

        <!-- Form -->
        <form id="uploadForm" action="https://api.github.com/repos/yourusername/yourrepo/contents/path/to/file" method="POST">
            <input type="hidden" name="access_token" value="YOUR_GITHUB_TOKEN">

            <!-- Repository -->
            <div class="form-group">
                <label class="form-label" for="repository">
                    Repository <span class="required">*</span>
                </label>
                <input type="text" id="repository" name="repository" class="form-control" placeholder="username/repository-name" required>
            </div>

            <!-- File Path -->
            <div class="form-group">
                <label class="form-label" for="filePath">
                    File Path <span class="required">*</span>
                </label>
                <input type="text" id="filePath" name="filePath" class="form-control" placeholder="path/to/file.ext" required>
            </div>

            <!-- Branch -->
            <div class="form-group">
                <label class="form-label" for="branch">Branch</label>
                <input type="text" id="branch" name="branch" class="form-control" placeholder="main" value="main">
            </div>

            <!-- Commit Message -->
            <div class="form-group">
                <label class="form-label" for="commitMessage">
                    Commit Message <span class="required">*</span>
                </label>
                <input type="text" id="commitMessage" name="commitMessage" class="form-control" placeholder="Add/Update file" required>
            </div>

            <!-- File Upload -->
            <div class="form-group">
                <label class="form-label" for="fileInput">
                    Select File <span class="required">*</span>
                </label>
                <input type="file" id="fileInput" class="form-control file-input" accept="*/*" required>
                <div id="fileInfo" class="file-info" style="display: none;">
                    <i class="bi bi-file-earmark-check"></i>
                    <span>Selected: <span class="file-name" id="fileNameDisplay"></span></span>
                    <span style="margin-left: auto; color: var(--slate-400); font-size: 0.8rem;" id="fileSizeDisplay"></span>
                </div>
                <div class="form-hint">Maximum file size: 100MB</div>
            </div>

            <!-- Options -->
            <div class="checkbox-group">
                <input type="checkbox" id="overwrite" name="overwrite" value="true">
                <label for="overwrite">
                    Overwrite existing file if it exists
                </label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="autoCommit" name="autoCommit" value="true" checked>
                <label for="autoCommit">
                    Auto-commit after upload
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="bi bi-cloud-upload"></i>
                Upload to GitHub
            </button>
        </form>

        <!-- Progress Bar -->
        <div class="progress-container" id="progressContainer">
            <div class="progress-bar-wrapper">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            <div class="progress-text">
                <span id="progressLabel">Uploading...</span>
                <span id="progressPercentage">0%</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="upload-footer">
            <p>
                By uploading, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('uploadForm');
            const fileInput = document.getElementById('fileInput');
            const fileInfo = document.getElementById('fileInfo');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const fileSizeDisplay = document.getElementById('fileSizeDisplay');
            const submitBtn = document.getElementById('submitBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const progressLabel = document.getElementById('progressLabel');
            const progressPercentage = document.getElementById('progressPercentage');
            const alertSuccess = document.getElementById('alertSuccess');
            const alertError = document.getElementById('alertError');
            const alertInfo = document.getElementById('alertInfo');

            // Hide all alerts initially
            function hideAlerts() {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.classList.remove('active');
                });
            }
            hideAlerts();

            // Show alert
            function showAlert(alertElement, message) {
                hideAlerts();
                const messageSpan = alertElement.querySelector('span:last-child');
                if (messageSpan) {
                    messageSpan.textContent = message;
                }
                alertElement.classList.add('active');
                setTimeout(() => {
                    alertElement.classList.remove('active');
                }, 5000);
            }

            // File selection handler
            fileInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const size = (file.size / 1024 / 1024).toFixed(2);
                    fileNameDisplay.textContent = file.name;
                    fileSizeDisplay.textContent = size + ' MB';
                    fileInfo.style.display = 'flex';

                    // Check file size (100MB limit)
                    if (file.size > 100 * 1024 * 1024) {
                        showAlert(alertError, 'File is too large. Maximum size is 100MB.');
                        this.value = '';
                        fileInfo.style.display = 'none';
                        return;
                    }
                }
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validate form
                const repository = document.getElementById('repository').value.trim();
                const filePath = document.getElementById('filePath').value.trim();
                const commitMessage = document.getElementById('commitMessage').value.trim();
                const file = fileInput.files[0];

                if (!repository) {
                    showAlert(alertError, 'Please enter a repository name.');
                    return;
                }

                if (!filePath) {
                    showAlert(alertError, 'Please enter a file path.');
                    return;
                }

                if (!commitMessage) {
                    showAlert(alertError, 'Please enter a commit message.');
                    return;
                }

                if (!file) {
                    showAlert(alertError, 'Please select a file to upload.');
                    return;
                }

                // Show progress
                progressContainer.classList.add('active');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Uploading...';

                // Simulate progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 95) {
                        progress = 95;
                        clearInterval(interval);
                    }
                    progressBar.style.width = progress + '%';
                    progressPercentage.textContent = Math.round(progress) + '%';
                }, 200);

                // Simulate upload (replace with actual API call)
                setTimeout(() => {
                    clearInterval(interval);
                    progressBar.style.width = '100%';
                    progressPercentage.textContent = '100%';
                    progressLabel.textContent = 'Upload complete!';

                    showAlert(alertSuccess, 'File uploaded successfully to ' + repository + ' at ' + filePath);

                    // Reset form
                    setTimeout(() => {
                        form.reset();
                        fileInfo.style.display = 'none';
                        progressContainer.classList.remove('active');
                        progressBar.style.width = '0%';
                        progressPercentage.textContent = '0%';
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-cloud-upload"></i> Upload to GitHub';
                    }, 2000);

                }, 3000);

                /*
                // ACTUAL API CALL TO GITHUB
                const reader = new FileReader();
                reader.onload = function() {
                    const content = reader.result.split(',')[1]; // Remove data URL prefix
                    const apiUrl = `https://api.github.com/repos/${repository}/contents/${filePath}`;

                    fetch(apiUrl, {
                        method: 'PUT',
                        headers: {
                            'Authorization': 'token ' + document.querySelector('input[name="access_token"]').value,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            message: commitMessage,
                            content: content,
                            branch: document.getElementById('branch').value || 'main',
                            sha: '' // Get SHA for existing file if overwriting
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        clearInterval(interval);
                        if (data.content) {
                            progressBar.style.width = '100%';
                            progressPercentage.textContent = '100%';
                            progressLabel.textContent = 'Upload complete!';
                            showAlert(alertSuccess, 'File uploaded successfully!');
                        } else {
                            showAlert(alertError, data.message || 'Upload failed');
                        }
                    })
                    .catch(error => {
                        clearInterval(interval);
                        showAlert(alertError, 'Error: ' + error.message);
                    });
                };
                reader.readAsDataURL(file);
                */
            });
        });
    </script>

</body>
</html>
