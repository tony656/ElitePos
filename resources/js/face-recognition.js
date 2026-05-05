/**
 * Face Recognition Continuous Monitoring
 * This script runs on all pages when face recognition is enabled
 * It continuously scans the user's face and logs out if an unknown face is detected for >5 seconds
 */

class FaceRecognitionMonitor {
    constructor() {
        this.stream = null;
        this.detectionInterval = null;
        this.countdownInterval = null;
        this.countdown = 5;
        this.isVerified = false;
        this.unknownFaceStartTime = null;
        this.verificationCooldown = 3000; // 3 seconds between checks
        this.lastCheckTime = 0;
        this.faceApiLoaded = false;
        this.isActive = false;
        this.userId = null;
        this.sessionId = null;
        this.csrfToken = null;
        this.apiBaseUrl = '/';
        
        // Configuration
        this.config = {
            checkInterval: 2000, // Check every 2 seconds
            unknownFaceTimeout: 5, // 5 seconds before logout
            confidenceThreshold: 0.6 // 60% match required
        };
        
        this.init();
    }
    
    async init() {
        // Check if face recognition is enabled
        const enabled = await this.checkIfEnabled();
        if (!enabled) {
            console.log('Face recognition is disabled');
            return;
        }
        
        this.userId = document.querySelector('meta[name="user-id"]')?.content;
        this.sessionId = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!this.userId || !this.csrfToken) {
            console.warn('Face recognition: Missing user ID or CSRF token');
            return;
        }
        
        this.isActive = true;
        console.log('Face recognition monitoring started');
        
        // Load face-api.js
        await this.loadFaceApi();
        
        // Start camera and monitoring
        await this.startCamera();
    }
    
    async checkIfEnabled() {
        try {
            const response = await fetch('/api/system-status', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            return data.face_recognition_enabled === true;
        } catch (error) {
            console.error('Failed to check system status:', error);
            return false;
        }
    }
    
    async loadFaceApi() {
        // Use the shared FaceApiLoader if available
        if (typeof FaceApiLoader !== 'undefined') {
            await FaceApiLoader.load();
            this.faceApiLoaded = true;
            return;
        }
        
        // Fallback to direct loading
        if (window.faceapi) {
            this.faceApiLoaded = true;
            await this.loadModels();
            return;
        }
        
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js';
            script.async = true;
            script.onload = async () => {
                await this.loadModels();
                this.faceApiLoaded = true;
                resolve();
            };
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }
    
    async loadModels() {
        const MODEL_URL = 'https://unpkg.com/face-api.js@0.22.2/weights';
        
        try {
            if (typeof faceapi === 'undefined') {
                throw new Error('face-api library not available');
            }
            
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            console.log('Face recognition models loaded');
            this.faceApiLoaded = true;
        } catch (error) {
            console.error('Failed to load face models:', error);
            throw error;
        }
    }
    
    async startCamera() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user'
                },
                audio: false
            });
            
            const video = document.createElement('video');
            video.autoplay = true;
            video.playsInline = true;
            video.srcObject = this.stream;
            video.style.display = 'none';
            document.body.appendChild(video);
            
            // Wait for video to be ready
            await new Promise(resolve => {
                video.onloadedmetadata = () => {
                    video.play();
                    resolve();
                };
            });
            
            console.log('Camera started for face recognition');
            this.startMonitoring(video);
            
        } catch (error) {
            console.error('Camera access denied:', error);
            this.showCameraWarning();
        }
    }
    
    startMonitoring(video) {
        this.detectionInterval = setInterval(async () => {
            if (!this.faceApiLoaded || !this.isActive || video.paused || video.ended) {
                return;
            }
            
            const now = Date.now();
            if (now - this.lastCheckTime < this.verificationCooldown) {
                return;
            }
            
            try {
                const detection = await faceapi.detectSingleFace(video).withFaceDescriptor();
                
                if (detection) {
                    // Face detected - verify it
                    await this.verifyFace(detection.descriptor);
                } else {
                    // No face detected - treat as unknown
                    this.handleNoFaceDetected();
                }
            } catch (error) {
                console.error('Face detection error:', error);
            }
        }, this.config.checkInterval);
    }
    
    async verifyFace(faceDescriptor) {
        // Reset countdown if face is verified
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
            this.countdownInterval = null;
        }
        
        // If already verified recently, skip
        if (this.isVerified) {
            return;
        }
        
        const faceEncoding = Array.from(faceDescriptor);
        
        try {
            const response = await fetch('/face/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    face_encoding: faceEncoding,
                    session_id: this.sessionId
                })
            });
            
            const result = await response.json();
            this.lastCheckTime = Date.now();
            
            if (result.success) {
                // Face verified successfully
                this.isVerified = true;
                this.unknownFaceStartTime = null;
                console.log('Face verified:', result.confidence + '%');
            } else {
                // Verification failed - start or continue countdown
                this.handleFailedVerification();
            }
        } catch (error) {
            console.error('Verification error:', error);
            this.handleFailedVerification();
        }
    }
    
    handleFailedVerification() {
        if (this.isVerified) {
            this.isVerified = false;
        }
        
        if (!this.unknownFaceStartTime) {
            this.unknownFaceStartTime = Date.now();
            console.warn('Unknown face detected. Starting countdown...');
        }
        
        const elapsed = (Date.now() - this.unknownFaceStartTime) / 1000;
        const remaining = Math.max(0, this.config.unknownFaceTimeout - elapsed);
        
        // Show countdown UI
        this.showCountdownUI(remaining);
        
        if (remaining <= 0) {
            // Time's up - logout
            this.triggerLogout();
        }
    }
    
    handleNoFaceDetected() {
        if (this.isVerified) {
            this.isVerified = false;
        }
        
        if (!this.unknownFaceStartTime) {
            this.unknownFaceStartTime = Date.now();
        }
        
        const elapsed = (Date.now() - this.unknownFaceStartTime) / 1000;
        const remaining = Math.max(0, this.config.unknownFaceTimeout - elapsed);
        
        this.showCountdownUI(remaining);
        
        if (remaining <= 0) {
            this.triggerLogout();
        }
    }
    
    showCountdownUI(remaining) {
        // Create or update countdown overlay
        let overlay = document.getElementById('face-recognition-countdown');
        
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'face-recognition-countdown';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(220, 53, 69, 0.95);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                z-index: 999999;
                color: white;
                font-family: 'Roboto', sans-serif;
            `;
            
            overlay.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 5rem; margin-bottom: 1rem;"></i>
                <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem;">SECURITY ALERT</h1>
                <p style="font-size: 1.5rem; margin-bottom: 2rem;">Unrecognized face detected!</p>
                <div style="font-size: 4rem; font-weight: 700;" id="face-countdown-number">5</div>
                <p style="font-size: 1.2rem; margin-top: 1rem;">You will be logged out in <span id="face-countdown-text">5</span> seconds</p>
            `;
            
            document.body.appendChild(overlay);
        }
        
        const numberEl = overlay.querySelector('#face-countdown-number');
        const textEl = overlay.querySelector('#face-countdown-text');
        
        if (numberEl && textEl) {
            numberEl.textContent = Math.ceil(remaining);
            textEl.textContent = Math.ceil(remaining);
        }
    }
    
    hideCountdownUI() {
        const overlay = document.getElementById('face-recognition-countdown');
        if (overlay) {
            overlay.remove();
        }
    }
    
    triggerLogout() {
        console.log('Logging out due to unrecognized face');
        
        // Show final message
        let overlay = document.getElementById('face-recognition-countdown');
        if (overlay) {
            overlay.innerHTML = `
                <i class="bi bi-shield-lock-fill" style="font-size: 5rem; margin-bottom: 1rem;"></i>
                <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">LOGGING OUT</h1>
                <p style="font-size: 1.2rem;">For your security, you have been logged out.</p>
                <p style="font-size: 1rem; margin-top: 1rem;">Redirecting to login...</p>
            `;
        }
        
        // Stop monitoring
        this.stop();
        
        // Logout after 2 seconds
        setTimeout(() => {
            window.location.href = '/signout';
        }, 2000);
    }
    
    stop() {
        this.isActive = false;
        
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        
        if (this.detectionInterval) {
            clearInterval(this.detectionInterval);
            this.detectionInterval = null;
        }
        
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
            this.countdownInterval = null;
        }
        
        this.hideCountdownUI();
        console.log('Face recognition monitoring stopped');
    }
    
    showCameraWarning() {
        const warning = document.createElement('div');
        warning.id = 'face-recognition-camera-warning';
        warning.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            z-index: 999999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            max-width: 300px;
        `;
        warning.innerHTML = `
            <strong><i class="bi bi-camera-video-off me-2"></i>Camera Required</strong><br>
            <small>Face recognition is enabled but camera access is denied. You may be logged out automatically.</small>
        `;
        document.body.appendChild(warning);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize for authenticated users
    const isAuthenticated = document.querySelector('meta[name="user-id"]');
    if (isAuthenticated) {
        window.faceRecognitionMonitor = new FaceRecognitionMonitor();
    }
});

// Shared Face API Loader - ensures only one load happens across all scripts
class FaceApiLoader {
    static load() {
        if (!window._faceApiLoadPromise) {
            window._faceApiLoadPromise = this.loadFaceApi();
        }
        return window._faceApiLoadPromise;
    }
    
    static async loadFaceApi() {
        // If already loaded, return immediately
        if (window.faceapi) {
            console.log('face-api already present');
            return;
        }
        
        return new Promise((resolve, reject) => {
            console.log('Loading face-api.js from CDN...');
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js';
            script.async = true;
            script.onload = async () => {
                console.log('face-api.js loaded, loading models...');
                try {
                    await this.loadModels();
                    console.log('Face API fully loaded and ready');
                    resolve();
                } catch (error) {
                    reject(error);
                }
            };
            script.onerror = () => reject(new Error('Failed to load face-api.js'));
            document.head.appendChild(script);
        });
    }
    
    static async loadModels() {
        const MODEL_URL = 'https://unpkg.com/face-api.js@0.22.2/weights';
        
        if (typeof faceapi === 'undefined') {
            throw new Error('face-api library not available');
        }
        
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);
    }
    
    static isLoaded() {
        return !!(window.faceapi && window.faceapi.nets &&
                  window.faceapi.nets.tinyFaceDetector &&
                  window.faceapi.nets.tinyFaceDetector.isLoaded());
    }
}

// Make available globally
window.FaceApiLoader = FaceApiLoader;

// Export for use in other scripts
window.FaceRecognitionMonitor = FaceRecognitionMonitor;