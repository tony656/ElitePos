<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Face Verification</title>
    @include('links')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .verification-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 90%;
        }
        
        .camera-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 1.5rem;
        }
        
        #video {
            width: 100%;
            border-radius: 10px;
            background: #000;
        }
        
        .face-outline {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
            height: 240px;
            border: 3px solid #28a745;
            border-radius: 50% 50% 45% 45%;
            pointer-events: none;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { 
                opacity: 0.8;
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
            }
            50% { 
                opacity: 1;
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }
        }
        
        .status-indicator {
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .status-verifying {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .countdown {
            font-size: 3rem;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            margin: 1rem 0;
        }
        
        .progress-bar {
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            transition: width 0.3s ease;
        }
        
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="text-center mb-4">
            <i class="bi bi-shield-check" style="font-size: 3rem; color: #28a745;"></i>
            <h3 class="mt-2">Face Verification Required</h3>
            <p class="text-muted">Please verify your identity to continue</p>
        </div>

        <div id="statusIndicator" class="status-indicator status-verifying">
            <i class="bi bi-camera-video me-2"></i>
            <span id="statusText">Initializing camera...</span>
        </div>

        <div class="camera-wrapper">
            <video id="video" autoplay playsinline></video>
            <div class="face-outline" id="faceOutline"></div>
        </div>

        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 0%"></div>
        </div>

        <div class="text-center mb-3">
            <div id="countdown" class="countdown" style="display: none;">5</div>
            <p id="instruction" class="mb-0">Position your face within the outline</p>
        </div>

        <div class="d-grid gap-2">
            <button id="startVerification" class="btn btn-primary">
                <i class="bi bi-play-circle me-2"></i>Start Verification
            </button>
            <button id="cancelBtn" class="btn btn-outline-secondary" style="display: none;">
                <i class="bi bi-x-circle me-2"></i>Cancel
            </button>
        </div>

        <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('video');
            const startBtn = document.getElementById('startVerification');
            const cancelBtn = document.getElementById('cancelBtn');
            const statusIndicator = document.getElementById('statusIndicator');
            const statusText = document.getElementById('statusText');
            const progressFill = document.getElementById('progressFill');
            const errorMessage = document.getElementById('errorMessage');

            let stream = null;
            let isVerifying = false;
            let verificationInterval = null;

            // Auto-start verification
            startVerification();

            startBtn.addEventListener('click', startVerification);
            cancelBtn.addEventListener('click', cancelVerification);

            async function startVerification() {
                if (isVerifying) return;
                
                isVerifying = true;
                startBtn.style.display = 'none';
                cancelBtn.style.display = 'block';
                errorMessage.style.display = 'none';
                
                try {
                    // Wait for face-api to be ready
                    await waitForFaceApiReady();
                    
                    // Start camera
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { 
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user'
                        } 
                    });
                    video.srcObject = stream;
                    
                    statusText.textContent = 'Looking for face...';
                    updateStatus('verifying');
                    
                    // Start continuous verification
                    startContinuousVerification();
                    
                } catch (err) {
                    showError('Camera access denied: ' + err.message);
                    isVerifying = false;
                    startBtn.style.display = 'block';
                    cancelBtn.style.display = 'none';
                }
            }

            function startContinuousVerification() {
                let progress = 0;
                progressFill.style.width = '0%';
                
                // Simulate verification progress
                verificationInterval = setInterval(() => {
                    if (progress < 100) {
                        progress += 2;
                        progressFill.style.width = progress + '%';
                    }
                }, 100);

                // Actual verification every 2 seconds
                const verifyInterval = setInterval(async () => {
                    if (!isVerifying) {
                        clearInterval(verifyInterval);
                        return;
                    }
                    
                    await verifyFace();
                }, 2000);
            }

            async function verifyFace() {
                if (!stream || !window.faceapi) return;

                try {
                    // Detect face and extract encoding using global face-api
                    const detection = await window.faceapi.detectSingleFace(video).withFaceDescriptor();
                    
                    if (!detection) {
                        // No face detected - continue checking
                        return;
                    }

                    // Convert Float32Array to regular array for JSON serialization
                    const faceEncoding = Array.from(detection.descriptor);
                    
                    // Send to server
                    const response = await fetch('{{ route('face.verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            face_encoding: faceEncoding,
                            session_id: '{{ session()->getId() }}'
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Verification successful
                        clearInterval(verificationInterval);
                        statusText.textContent = 'Verification successful!';
                        updateStatus('success');
                        
                        // Redirect after short delay
                        setTimeout(() => {
                            window.location.href = '{{ session()->get("url.intended") ?? route("user.dashboard") }}';
                        }, 1000);
                        
                    } else if (result.requires_registration) {
                        // User needs to register face first
                        clearInterval(verificationInterval);
                        showError('No face registered. Please register your face first.');
                        cancelVerification();
                        
                        // Redirect to registration after delay
                        setTimeout(() => {
                            window.location.href = '{{ route('face.register.page') }}';
                        }, 2000);
                    }
                    // If verification fails, continue checking
                    
                } catch (err) {
                    console.error('Verification error:', err);
                }
            }

            function updateStatus(type) {
                statusIndicator.className = 'status-indicator';
                if (type === 'verifying') {
                    statusIndicator.classList.add('status-verifying');
                    statusText.innerHTML = '<i class="bi bi-camera-video me-2"></i>Verifying your face...';
                } else if (type === 'success') {
                    statusIndicator.classList.add('status-success');
                    statusText.innerHTML = '<i class="bi bi-check-circle me-2"></i>Verified successfully!';
                } else if (type === 'error') {
                    statusIndicator.classList.add('status-error');
                }
            }

            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.style.display = 'block';
                updateStatus('error');
                statusText.textContent = 'Verification failed';
            }

            function cancelVerification() {
                isVerifying = false;
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                    stream = null;
                }
                if (verificationInterval) {
                    clearInterval(verificationInterval);
                }
                startBtn.style.display = 'block';
                cancelBtn.style.display = 'none';
                progressFill.style.width = '0%';
                statusText.textContent = 'Verification cancelled';
                updateStatus('verifying');
            }

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

            // Clean up on page unload
            window.addEventListener('beforeunload', () => {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });
        });
    </script>
</body>
</html>