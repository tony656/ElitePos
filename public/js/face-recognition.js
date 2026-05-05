/**
 * Face Recognition Module
 * Provides continuous face scanning and auto-logout for unknown faces
 */

class FaceRecognition {
    constructor() {
        this.enabled = false;
        this.stream = null;
        this.isActive = false;
        this.verificationInterval = null;
        this.unknownFaceStartTime = null;
        this.UNKNOWN_FACE_TIMEOUT = 5; // seconds before auto-logout
        this.VERIFICATION_INTERVAL = 2; // verify every 2 seconds
        this.sessionId = null;
        this.autoLogoutWarningShown = false;
        this.faceApiLoaded = false;
        this.video = null;
        
        this.init();
    }

    async init() {
        // Check if face recognition is enabled
        await this.checkSettings();
        
        // Only start if enabled and user is authenticated
        if (this.enabled && this.isUserAuthenticated()) {
            this.start();
        }
    }

    isUserAuthenticated() {
        // Check if user is logged in by looking for CSRF token or specific elements
        return document.querySelector('meta[name="csrf-token"]') !== null;
    }

    async checkSettings() {
        try {
            const response = await fetch('/api/system-status', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.enabled = data.face_recognition_enabled || false;
                this.UNKNOWN_FACE_TIMEOUT = data.face_verification_timeout || 5;
            } else {
                console.error('System status error:', response.status, response.statusText);
            }
        } catch (err) {
            console.log('Could not fetch system status:', err);
        }
    }

    start() {
        if (this.isActive) return;
        
        this.isActive = true;
        this.sessionId = this.getSessionId();
        
        // Start camera after a short delay to not block page load
        setTimeout(() => {
            this.startCamera();
            this.startContinuousVerification();
        }, 2000);
    }

    stop() {
        this.isActive = false;
        
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        
        if (this.verificationInterval) {
            clearInterval(this.verificationInterval);
            this.verificationInterval = null;
        }
        
        if (this.video) {
            this.video = null;
        }
        
        this.unknownFaceStartTime = null;
        this.autoLogoutWarningShown = false;
    }

    async startCamera() {
        try {
            // Ensure face-api is loaded before starting camera
            if (!this.faceApiLoaded) {
                await this.ensureFaceApiLoaded();
            }
            
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 320 },
                    height: { ideal: 240 },
                    facingMode: 'user'
                },
                audio: false
            });
            
            // Create hidden video element for processing
            this.video = document.createElement('video');
            this.video.style.display = 'none'; // Hide the video element
            this.video.srcObject = this.stream;
            this.video.setAttribute('playsinline', ''); // Required for iOS
            await this.video.play();
            
            console.log('Camera started successfully');
            
        } catch (err) {
            console.warn('Face recognition: Camera access denied:', err);
            // Don't stop completely, just log
        }
    }

    async ensureFaceApiLoaded() {
        // Use the shared FaceApiLoader
        if (typeof FaceApiLoader !== 'undefined') {
            await FaceApiLoader.load();
            this.faceApiLoaded = true;
            return;
        }
        
        // Fallback: load directly if FaceApiLoader not available
        if (!window.faceapi) {
            await this.loadFaceApi();
        }
        this.faceApiLoaded = true;
    }

    async loadFaceApi() {
        return new Promise((resolve, reject) => {
            if (window.faceapi && this.faceApiLoaded) {
                console.log('face-api already loaded and ready');
                resolve();
                return;
            }
            
            if (window.faceapi && !this.faceApiLoaded) {
                this.loadFaceModels().then(resolve).catch(reject);
                return;
            }
            
            console.log('Loading face-api.js script...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js';
            script.async = true;
            script.onload = async () => {
                console.log('face-api.js script loaded, loading models...');
                try {
                    await new Promise(resolve => setTimeout(resolve, 200));
                    await this.loadFaceModels();
                    resolve();
                } catch (error) {
                    reject(error);
                }
            };
            script.onerror = () => reject(new Error('Failed to load face-api.js script'));
            document.head.appendChild(script);
        });
    }

    async loadFaceModels() {
        const MODEL_URL = '/models';
        
        try {
            if (typeof faceapi === 'undefined') {
                throw new Error('face-api library not available');
            }
            
            console.log('Loading face detection models...');
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
            console.log('Face recognition models loaded successfully');
            this.faceApiLoaded = true;
        } catch (error) {
            console.error('Failed to load face models:', error);
            throw error;
        }
    }

    startContinuousVerification() {
        this.verificationInterval = setInterval(() => {
            if (this.isActive && this.stream && this.video && this.faceApiLoaded && this.video.readyState >= 2) {
                this.verifyFace();
            }
        }, this.VERIFICATION_INTERVAL * 1000);
    }

    async verifyFace() {
        if (!this.video || !this.stream || !this.faceApiLoaded) return;

        try {
            // CORRECTED: Use the proper API method
            const detection = await faceapi
                .detectSingleFace(this.video, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptor();
            
            if (!detection) {
                this.handleNoFaceDetected();
                return;
            }

            // Extract face descriptor
            const faceEncoding = Array.from(detection.descriptor);
            
            // Send to server for verification
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                console.warn('No CSRF token found');
                return;
            }
            
            const response = await fetch('/face/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    face_encoding: faceEncoding,
                    session_id: this.sessionId
                })
            });

            const result = await response.json();

            if (result.success) {
                this.unknownFaceStartTime = null;
                this.autoLogoutWarningShown = false;
                console.log('Face verified:', result.confidence + '%');
                this.hideCountdownUI();
            } else {
                this.handleFailedVerification();
            }

        } catch (err) {
            console.error('Face verification error:', err);
        }
    }

    handleFailedVerification() {
        const now = Date.now();
        
        if (!this.unknownFaceStartTime) {
            this.unknownFaceStartTime = now;
        }
        
        const elapsed = (now - this.unknownFaceStartTime) / 1000;
        const remaining = Math.max(0, this.UNKNOWN_FACE_TIMEOUT - elapsed);
        
        this.showCountdownUI(remaining);
        
        if (remaining <= 0) {
            this.triggerLogout();
        }
    }

    handleNoFaceDetected() {
        if (this.isActive) {
            this.handleFailedVerification();
        }
    }
    
    hideCountdownUI() {
        const overlay = document.getElementById('face-recognition-countdown');
        if (overlay) {
            overlay.remove();
        }
    }
    
    showCountdownUI(remaining) {
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
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            `;
            
            overlay.innerHTML = `
                <div style="text-align: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16" style="margin-bottom: 1rem;">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <h1 style="font-size: 3rem; font-weight: 700; margin-bottom: 1rem;">SECURITY ALERT</h1>
                    <p style="font-size: 1.5rem; margin-bottom: 2rem;">Unrecognized face detected!</p>
                    <div style="font-size: 5rem; font-weight: 700; margin-bottom: 1rem;" id="face-countdown-number">${Math.ceil(remaining)}</div>
                    <p style="font-size: 1.2rem;">You will be logged out in <span id="face-countdown-text">${Math.ceil(remaining)}</span> seconds</p>
                </div>
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
    
    triggerLogout() {
        console.log('Logging out due to unrecognized face');
        
        let overlay = document.getElementById('face-recognition-countdown');
        if (overlay) {
            overlay.innerHTML = `
                <div style="text-align: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16" style="margin-bottom: 1rem;">
                        <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.265 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5z"/>
                    </svg>
                    <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem;">LOGGING OUT</h1>
                    <p style="font-size: 1.2rem;">For your security, you have been logged out.</p>
                    <p style="font-size: 1rem; margin-top: 1rem;">Redirecting to login...</p>
                </div>
            `;
        }
        
        this.stop();
        
        setTimeout(() => {
            window.location.href = '/signout';
        }, 2000);
    }

    getSessionId() {
        const match = document.cookie.match(/laravel_session=([^;]+)/);
        return match ? match[1] : 'unknown-' + Date.now();
    }
}

// Shared Face API Loader - ensures only one load happens
class FaceApiLoader {
    static load() {
        if (!window._faceApiLoadPromise) {
            window._faceApiLoadPromise = this.loadFaceApi();
        }
        return window._faceApiLoadPromise;
    }
    
    static async loadFaceApi() {
        // If already loaded, return immediately
        if (window.faceapi && window.faceapi.nets && window.faceapi.nets.tinyFaceDetector && 
            window.faceapi.nets.tinyFaceDetector.isLoaded && window.faceapi.nets.tinyFaceDetector.isLoaded()) {
            console.log('face-api already loaded');
            return;
        }
        
        return new Promise((resolve, reject) => {
            console.log('Loading face-api.js from CDN...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js';
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
        const MODEL_URL = '/models';
        
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
                  window.faceapi.nets.tinyFaceDetector.isLoaded &&
                  window.faceapi.nets.tinyFaceDetector.isLoaded());
    }
}

// Make available globally
if (typeof window !== 'undefined') {
    window.FaceApiLoader = FaceApiLoader;
    
    // Wait for DOM to be ready before initializing
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.faceRecognition = new FaceRecognition();
            window.faceRecognitionMonitor = window.faceRecognition;
        });
    } else {
        window.faceRecognition = new FaceRecognition();
        window.faceRecognitionMonitor = window.faceRecognition;
    }
}