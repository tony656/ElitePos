<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Face Registration - Admin</title>
    @include('links')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <style>
        .camera-container {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
        }
        
        #video {
            width: 100%;a
            border-radius: 10px;
            background: #000;
        }
        
        #canvas {
            display: none;
        }
        
        .camera-controls {
            margin-top: 1rem;
            text-align: center;
        }
        
        .face-guide {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border: 3px dashed #28a745;
            border-radius: 50%;
            pointer-events: none;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }
        
        .status-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
        }
        
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .encodings-list {
            margin-top: 2rem;
        }
        
        .encoding-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .encoding-item:last-child {
            border-bottom: none;
        }
        
        .badge-active {
            background-color: #28a745;
        }
        
        .badge-inactive {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <main class="row">
        @include('admin/sidenav')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
            <div class="container mt-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><i class="bi bi-person-badge me-2"></i>Face Registration (Admin)</h4>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                    </a>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Important:</strong> Select the user whose face you want to register, then position their face inside the circle. Ensure good lighting and look directly at the camera. Click "Capture Face" when ready.
                </div>

                <!-- User Selection Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Select User</h5>
                    </div>
                    <div class="card-body">
                        <form id="userSelectionForm">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="userSelect" class="form-label">Choose User</label>
                                    <select class="form-select" id="userSelect" name="user_id" required>
                                        <option value="">-- Select a user --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->levelStatus ?? 'User') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" id="confirmUser" class="btn btn-primary w-100">
                                        <i class="bi bi-check-circle me-1"></i>Confirm Selection
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div id="selectedUserDisplay" class="mt-3" style="display: none;">
                            <div class="alert alert-success">
                                <strong>Selected User:</strong> <span id="selectedUserName"></span>
                                <button type="button" class="btn btn-sm btn-outline-danger float-end" id="changeUser">
                                    <i class="bi bi-arrow-clockwise"></i> Change
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="camera-container">
                            <video id="video" autoplay playsinline></video>
                            <canvas id="canvas"></canvas>
                            <div class="face-guide" id="faceGuide"></div>
                        </div>

                        <div class="camera-controls">
                            <button type="button" id="startCamera" class="btn btn-primary me-2">
                                <i class="bi bi-camera me-1"></i>Start Camera
                            </button>
                            <button type="button" id="captureFace" class="btn btn-success" disabled>
                                <i class="bi bi-camera-fill me-1"></i>Capture Face
                            </button>
                            <button type="button" id="stopCamera" class="btn btn-danger" disabled>
                                <i class="bi bi-camera-video-off me-1"></i>Stop Camera
                            </button>
                        </div>

                        <div id="statusMessage" class="status-message" style="display: none;"></div>
                    </div>
                </div>

                <div class="encodings-list card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Registered Faces</h5>
                    </div>
                    <div class="card-body">
                        <div id="encodingsList">
                            <!-- Face encodings will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </main>

    <script>
        // Load face-api.js and initialize
        async function loadFaceAPI() {
            return new Promise((resolve, reject) => {
                if (window.faceapi) {
                    console.log('face-api already loaded');
                    resolve();
                    return;
                }

                console.log('Loading face-api.js...');
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js';
                script.onload = async () => {
                    console.log('face-api.js loaded, loading models...');
                    try {
                        // Load the required models from the public directory
                        await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
                        await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
                        await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
                        console.log('Face detection models loaded successfully');
                        resolve();
                    } catch (error) {
                        console.error('Error loading models:', error);
                        reject(error);
                    }
                };
                script.onerror = () => reject(new Error('Failed to load face-api.js'));
                document.head.appendChild(script);
            });
        }

        document.addEventListener('DOMContentLoaded', async function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const startBtn = document.getElementById('startCamera');
            const captureBtn = document.getElementById('captureFace');
            const stopBtn = document.getElementById('stopCamera');
            const statusDiv = document.getElementById('statusMessage');
            const encodingsList = document.getElementById('encodingsList');
            const userSelect = document.getElementById('userSelect');
            const confirmUserBtn = document.getElementById('confirmUser');
            const selectedUserDisplay = document.getElementById('selectedUserDisplay');
            const selectedUserName = document.getElementById('selectedUserName');
            const changeUserBtn = document.getElementById('changeUser');

            let stream = null;
            let isProcessing = false;
            let selectedUserId = null;

            // User selection handlers
            confirmUserBtn.addEventListener('click', function() {
                const userId = userSelect.value;
                if (!userId) {
                    alert('Please select a user first');
                    return;
                }
                selectedUserId = userId;
                const selectedOption = userSelect.options[userSelect.selectedIndex];
                selectedUserName.textContent = selectedOption.text;
                selectedUserDisplay.style.display = 'block';
                userSelect.disabled = true;
                confirmUserBtn.disabled = true;
                showStatus('User selected. You can now capture their face.', 'success');
            });

            changeUserBtn.addEventListener('click', function() {
                selectedUserId = null;
                userSelect.disabled = false;
                confirmUserBtn.disabled = false;
                selectedUserDisplay.style.display = 'none';
                showStatus('Please select a user.', 'info');
            });

            // Load face recognition library first
            try {
                showStatus('Loading face recognition library...', 'info');
                await loadFaceAPI();
                showStatus('Face recognition ready! Click "Start Camera" to begin.', 'success');
            } catch (err) {
                showStatus('Failed to load face recognition: ' + err.message, 'error');
                console.error(err);
            }

            // Load existing encodings
            loadEncodings();

            // Start camera
            startBtn.addEventListener('click', async function() {
                try {
                    if (!window.faceapi) {
                        throw new Error('Face recognition library not loaded yet');
                    }
                    
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user'
                        }
                    });
                    video.srcObject = stream;
                    await video.play();
                    
                    captureBtn.disabled = false;
                    stopBtn.disabled = false;
                    startBtn.disabled = true;
                    showStatus('Camera started. Position your face in the circle.', 'info');
                } catch (err) {
                    showStatus('Error: ' + err.message, 'error');
                }
            });

            // Capture face - FIXED VERSION
            captureBtn.addEventListener('click', async function() {
                if (isProcessing) {
                    showStatus('Please wait, still processing...', 'info');
                    return;
                }
                
                if (!stream || !video.srcObject) {
                    showStatus('Please start the camera first', 'error');
                    return;
                }

                if (!selectedUserId) {
                    showStatus('Please select a user first', 'error');
                    return;
                }

                isProcessing = true;
                captureBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Processing...';
                captureBtn.disabled = true;

                try {
                    // Wait a bit for video to be ready
                    if (video.readyState < 2) {
                        await new Promise(resolve => setTimeout(resolve, 500));
                    }
                    
                    // FIXED: Proper face detection with all required methods
                    const detection = await faceapi
                        .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor();
                    
                    if (!detection) {
                        showStatus('No face detected. Please position your face clearly in the circle.', 'error');
                        isProcessing = false;
                        captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                        captureBtn.disabled = false;
                        return;
                    }

                    // Check if face is properly positioned (optional)
                    const landmarks = detection.landmarks;
                    if (!landmarks || landmarks.positions.length === 0) {
                        showStatus('Could not detect face landmarks. Please ensure your face is clearly visible.', 'error');
                        isProcessing = false;
                        captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                        captureBtn.disabled = false;
                        return;
                    }

                    // Convert Float32Array to regular array for JSON serialization
                    const faceEncoding = Array.from(detection.descriptor);
                    
                    console.log('Face detected with', faceEncoding.length, 'descriptors');
                    
                    // Send to server with selected user_id
                    const response = await fetch('{{ route('face.register') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            face_encoding: faceEncoding,
                            user_id: selectedUserId,
                            device_name: navigator.platform || 'Unknown',
                            browser: getBrowserName(),
                            os: getOSName()
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        showStatus('Face registered successfully!', 'success');
                        loadEncodings(selectedUserId); // Refresh the list for selected user
                        // Reset after success
                        setTimeout(() => {
                            isProcessing = false;
                            captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                            captureBtn.disabled = false;
                        }, 2000);
                    } else {
                        showStatus('Failed to register face: ' + (result.message || 'Unknown error'), 'error');
                        isProcessing = false;
                        captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                        captureBtn.disabled = false;
                    }
                } catch (err) {
                    console.error('Capture error:', err);
                    showStatus('Error: ' + err.message, 'error');
                    isProcessing = false;
                    captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                    captureBtn.disabled = false;
                }
            });

            // Stop camera
            stopBtn.addEventListener('click', function() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                    stream = null;
                }
                isProcessing = false;
                captureBtn.disabled = true;
                stopBtn.disabled = true;
                startBtn.disabled = false;
                captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                showStatus('Camera stopped.', 'info');
            });

            function loadEncodings(userId = null) {
                let url = '{{ route('face.encodings') }}';
                if (userId) {
                    url += '?user_id=' + userId;
                }
                
                fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayEncodings(data.data);
                    } else {
                        console.error('Failed to load encodings:', data.message);
                    }
                })
                .catch(err => {
                    console.error('Error loading encodings:', err);
                });
            }

            function displayEncodings(encodings) {
                if (!encodings || encodings.length === 0) {
                    encodingsList.innerHTML = '<p class="text-muted">No face encodings registered yet.</p>';
                    return;
                }

                let html = '<div class="list-group">';
                encodings.forEach(encoding => {
                    html += `
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>ID:</strong> ${encoding.id}<br>
                                    <strong>Registered:</strong> ${new Date(encoding.registered_at).toLocaleString()}<br>
                                    <small class="text-muted">
                                        ${encoding.device_name || 'Unknown device'} - 
                                        ${encoding.browser || 'Unknown browser'} - 
                                        ${encoding.os || 'Unknown OS'}
                                    </small>
                                </div>
                                <span class="badge ${encoding.is_active ? 'bg-success' : 'bg-secondary'}">
                                    ${encoding.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                encodingsList.innerHTML = html;
            }

            function showStatus(message, type) {
                statusDiv.textContent = message;
                statusDiv.className = 'status-message status-' + type;
                statusDiv.style.display = 'block';
                
                // Auto-hide after 5 seconds for success/info messages
                if (type !== 'error') {
                    setTimeout(() => {
                        if (statusDiv.textContent === message) {
                            statusDiv.style.display = 'none';
                        }
                    }, 5000);
                }
            }

            function getBrowserName() {
                const ua = navigator.userAgent;
                if (ua.includes('Chrome')) return 'Chrome';
                if (ua.includes('Firefox')) return 'Firefox';
                if (ua.includes('Safari')) return 'Safari';
                if (ua.includes('Edge')) return 'Edge';
                if (ua.includes('Opera')) return 'Opera';
                return 'Unknown';
            }

            function getOSName() {
                const ua = navigator.userAgent;
                if (ua.includes('Windows')) return 'Windows';
                if (ua.includes('Mac')) return 'MacOS';
                if (ua.includes('Linux')) return 'Linux';
                if (ua.includes('Android')) return 'Android';
                if (ua.includes('iOS')) return 'iOS';
                return 'Unknown';
            }
        });
    </script>
</body>
</html>