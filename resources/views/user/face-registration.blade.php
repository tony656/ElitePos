<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Face Registration</title>
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
            width: 100%;
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
    </style>
</head>
<body>
    <main class="row">
        @include('user/sidenav')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
            <div class="container mt-3">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4><i class="bi bi-person-badge me-2"></i>Face Registration</h4>
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
                    </a>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Important:</strong> Please position your face inside the circle. Ensure good lighting and look directly at the camera. Click "Capture Face" when ready.
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const startBtn = document.getElementById('startCamera');
            const captureBtn = document.getElementById('captureFace');
            const stopBtn = document.getElementById('stopCamera');
            const statusDiv = document.getElementById('statusMessage');
            const encodingsList = document.getElementById('encodingsList');

            let stream = null;
            let isCapturing = false;

            // Load existing encodings
            loadEncodings();

            // Wait for global face-api to be ready
            async function waitForFaceApiReady() {
                // Use the shared FaceApiLoader if available
                if (typeof FaceApiLoader !== 'undefined') {
                    await FaceApiLoader.load();
                    return;
                }
                
                // Fallback: wait for face-api to be available
                return new Promise((resolve, reject) => {
                    const checkInterval = setInterval(() => {
                        if (window.faceapi && window.faceapi.nets &&
                            window.faceapi.nets.tinyFaceDetector &&
                            window.faceapi.nets.tinyFaceDetector.isLoaded()) {
                            clearInterval(checkInterval);
                            resolve();
                        }
                    }, 100);
                    
                    // Timeout after 15 seconds
                    setTimeout(() => {
                        clearInterval(checkInterval);
                        reject(new Error('Face recognition is not available. Please wait or refresh.'));
                    }, 15000);
                });
            }

            // Start camera
            startBtn.addEventListener('click', async function() {
                try {
                    // Wait for face-api to be ready from global monitor
                    await waitForFaceApiReady();
                    
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user'
                        }
                    });
                    video.srcObject = stream;
                    captureBtn.disabled = false;
                    stopBtn.disabled = false;
                    startBtn.disabled = true;
                    showStatus('Camera started. Position your face in the circle.', 'info');
                } catch (err) {
                    showStatus('Error: ' + err.message, 'error');
                }
            });

            // Capture face
            captureBtn.addEventListener('click', function() {
                if (!isCapturing) {
                    startContinuousCapture();
                }
            });

            // Stop camera
            stopBtn.addEventListener('click', function() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                    stream = null;
                }
                if (isCapturing) {
                    isCapturing = false;
                }
                captureBtn.disabled = true;
                stopBtn.disabled = true;
                startBtn.disabled = false;
                showStatus('Camera stopped.', 'info');
            });

            async function startContinuousCapture() {
                isCapturing = true;
                captureBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Processing...';
                captureBtn.disabled = true;

                try {
                    // Use global face-api to detect face
                    const detection = await window.faceapi.detectSingleFace(video).withFaceDescriptor();
                    
                    if (!detection) {
                        showStatus('No face detected. Please position your face clearly.', 'error');
                        isCapturing = false;
                        captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                        captureBtn.disabled = false;
                        return;
                    }

                    // Convert Float32Array to regular array for JSON serialization
                    const faceEncoding = Array.from(detection.descriptor);
                    
                    // Send to server
                    const response = await fetch('{{ route('face.register') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            face_encoding: faceEncoding,
                            device_name: navigator.platform,
                            browser: getBrowserName(),
                            os: getOSName()
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        showStatus('Face registered successfully!', 'success');
                        loadEncodings();
                        // Reset capture button after 2 seconds
                        setTimeout(() => {
                            isCapturing = false;
                            captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                            captureBtn.disabled = false;
                        }, 2000);
                    } else {
                        showStatus('Failed to register face: ' + (result.message || 'Unknown error'), 'error');
                        isCapturing = false;
                        captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                        captureBtn.disabled = false;
                    }
                } catch (err) {
                    showStatus('Error: ' + err.message, 'error');
                    console.error('Capture error:', err);
                    isCapturing = false;
                    captureBtn.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Capture Face';
                    captureBtn.disabled = false;
                }
            }

            function loadEncodings() {
                fetch('{{ route('face.encodings') }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayEncodings(data.data);
                    }
                })
                .catch(err => {
                    console.error('Error loading encodings:', err);
                });
            }

            function displayEncodings(encodings) {
                if (encodings.length === 0) {
                    encodingsList.innerHTML = '<p class="text-muted">No face encodings registered yet.</p>';
                    return;
                }

                let html = '<div class="list-group">';
                encodings.forEach(encoding => {
                    html += `
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Registered:</strong> ${new Date(encoding.registered_at).toLocaleString()}<br>
                                    <small class="text-muted">
                                        ${encoding.device_name || 'Unknown device'} - 
                                        ${encoding.browser || 'Unknown browser'} - 
                                        ${encoding.os || 'Unknown OS'}
                                    </small>
                                </div>
                                <button class="btn btn-sm btn-danger" onclick="deleteEncoding(${encoding.id})">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                encodingsList.innerHTML = html;
            }

            window.deleteEncoding = function(id) {
                if (!confirm('Are you sure you want to delete this face encoding?')) {
                    return;
                }

                fetch(`/face/encoding/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatus('Face encoding deleted successfully', 'success');
                        loadEncodings();
                    } else {
                        showStatus('Failed to delete: ' + data.message, 'error');
                    }
                })
                .catch(err => {
                    showStatus('Error: ' + err.message, 'error');
                });
            };

            function showStatus(message, type) {
                statusDiv.textContent = message;
                statusDiv.className = 'status-message status-' + type;
                statusDiv.style.display = 'block';
            }

            function getBrowserName() {
                const ua = navigator.userAgent;
                if (ua.includes('Chrome')) return 'Chrome';
                if (ua.includes('Firefox')) return 'Firefox';
                if (ua.includes('Safari')) return 'Safari';
                if (ua.includes('Edge')) return 'Edge';
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