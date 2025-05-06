@extends('layouts.kiosk')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center mb-4">
        <h1>Face Recognition</h1>
        <p>Scan your face to verify identity</p>
    </div>

    <div class="face-scan-container mb-4">
        <div class="scan-frame">
            <video id="video" width="640" height="480" autoplay playsinline></video>
            <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
        </div>
        <div id="loading" class="loading-overlay" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div id="error-message" class="alert alert-danger" style="display: none;"></div>

    <button class="btn btn-primary btn-lg mb-3" id="startScan">
        Start Camera
    </button>

    <a href="{{ route('kiosk.back') }}" class="btn btn-link mt-4">
        <i class="fas fa-arrow-left"></i> Cancel
    </a>
</div>

@push('styles')
<style>
    .face-scan-container {
        position: relative;
        width: 640px;
        height: 480px;
        border: 2px solid #6f42c1;
        border-radius: 10px;
        overflow: hidden;
    }

    .scan-frame {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .scan-frame video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .scan-frame::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 300px;
        border: 2px solid #6f42c1;
        border-radius: 10px;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.11.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface@0.0.7"></script>

<script>
let video = document.getElementById('video');
let canvas = document.getElementById('canvas');
let context = canvas.getContext('2d');
let model = null;
let stream = null;
let detectionInterval = null;

// Check for camera support
function checkCameraSupport() {
    // Check if running in secure context
    if (!window.isSecureContext) {
        throw new Error('Camera access requires HTTPS or localhost');
    }

    // Check for different browser implementations
    if (!navigator.mediaDevices) {
        navigator.mediaDevices = {};
    }

    // Some browsers partially implement mediaDevices. We need a consistent API:
    if (!navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia = function(constraints) {
            const getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

            if (!getUserMedia) {
                throw new Error('getUserMedia is not implemented in this browser');
            }

            return new Promise((resolve, reject) => {
                getUserMedia.call(navigator, constraints, resolve, reject);
            });
        }
    }

    // Final check if getUserMedia is available
    if (!navigator.mediaDevices.getUserMedia) {
        throw new Error('Camera access is not supported by this browser');
    }
}

// Load the face detection model
async function loadModel() {
    try {
        document.getElementById('loading').style.display = 'flex';
        model = await blazeface.load();
        document.getElementById('loading').style.display = 'none';
    } catch (err) {
        showError('Error loading face detection model: ' + err.message);
    }
}

// Start video stream
async function startVideo() {
    try {
        checkCameraSupport();
        
        // Stop any existing stream
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        // Try different video constraints if the first one fails
        const constraints = {
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: 'user'
            }
        };

        try {
            stream = await navigator.mediaDevices.getUserMedia(constraints);
        } catch (err) {
            // If specific constraints fail, try with basic video
            console.warn('Failed with specific constraints, trying basic video');
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
        }
        
        video.srcObject = stream;
        
        // Wait for video to be ready
        await new Promise((resolve) => {
            video.onloadedmetadata = () => {
                video.play().catch(err => {
                    showError('Error playing video: ' + err.message);
                });
                resolve();
            };
        });

        // Start face detection
        startFaceDetection();
    } catch (err) {
        console.error('Camera access error:', err);
        if (err.name === 'NotAllowedError') {
            showError('Camera access denied. Please allow camera access and try again.');
        } else if (err.name === 'NotFoundError') {
            showError('No camera found. Please connect a camera and try again.');
        } else {
            showError('Error accessing camera: ' + err.message);
        }
        document.getElementById('startScan').style.display = 'block';
    }
}

// Stop video stream
function stopVideo() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    if (detectionInterval) {
        clearInterval(detectionInterval);
        detectionInterval = null;
    }
}

// Show error message
function showError(message) {
    const errorElement = document.getElementById('error-message');
    errorElement.textContent = message;
    errorElement.style.display = 'block';
    document.getElementById('loading').style.display = 'none';
}

// Detect faces in video stream
async function detectFaces() {
    if (!model || !video.readyState) return;
    
    try {
        const predictions = await model.estimateFaces(video, false);
        
        if (predictions.length > 0) {
            // Face detected, capture image
            context.drawImage(video, 0, 0, 640, 480);
            
            // Get face region with padding
            const face = predictions[0];
            const x = Math.max(0, Math.round(face.topLeft[0]) - 20);
            const y = Math.max(0, Math.round(face.topLeft[1]) - 20);
            const width = Math.min(640 - x, Math.round(face.bottomRight[0] - face.topLeft[0]) + 40);
            const height = Math.min(480 - y, Math.round(face.bottomRight[1] - face.topLeft[1]) + 40);

            // Create temporary canvas for face region
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = width;
            tempCanvas.height = height;
            const tempContext = tempCanvas.getContext('2d');
            tempContext.drawImage(canvas, x, y, width, height, 0, 0, width, height);

            // Extract face features
            const features = await extractFaceFeatures(tempCanvas);
            
            // Convert to base64
            const imageData = tempCanvas.toDataURL('image/jpeg', 0.8);
            
            // Stop detection and video
            stopVideo();
            
            // Send to server for verification
            try {
                const response = await fetch('{{ route("kiosk.verify-face") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        face_data: imageData,
                        face_features: features
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.href = '{{ route("kiosk.pin-verification") }}';
                } else {
                    showError(data.message || 'Face verification failed');
                    document.getElementById('startScan').style.display = 'block';
                }
            } catch (err) {
                showError('Error verifying face: ' + err.message);
                document.getElementById('startScan').style.display = 'block';
            }
        }
    } catch (err) {
        showError('Error during face detection: ' + err.message);
    }
}

// Extract face features using TensorFlow.js and BlazeFace
async function extractFaceFeatures(faceCanvas) {
    try {
        const predictions = await model.estimateFaces(faceCanvas, false);
        if (predictions.length === 0) {
            throw new Error('No face detected in the image');
        }
        
        // Get face landmarks
        const face = predictions[0];
        
        // Extract and normalize landmarks in the same way as face capture
        const features = [
            ...face.landmarks[0], // right eye
            ...face.landmarks[1], // left eye
            ...face.landmarks[2], // nose
            ...face.landmarks[3], // mouth
            ...face.landmarks[4], // right ear
            ...face.landmarks[5]  // left ear
        ].map(coord => coord / faceCanvas.width); // Normalize coordinates
        
        return features;
    } catch (error) {
        console.error('Feature extraction error:', error);
        throw new Error('Failed to extract face features: ' + error.message);
    }
}

// Start continuous face detection
function startFaceDetection() {
    if (detectionInterval) {
        clearInterval(detectionInterval);
    }
    detectionInterval = setInterval(detectFaces, 100);
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // Check camera support before loading model
        checkCameraSupport();
        
        // Show loading state
        document.getElementById('loading').style.display = 'flex';
        
        // Load model
        model = await blazeface.load();
        
        // Hide loading and show start button
        document.getElementById('loading').style.display = 'none';
        document.getElementById('startScan').style.display = 'block';
    } catch (err) {
        if (err.message.includes('HTTPS')) {
            showError('Camera access requires HTTPS. Please use a secure connection.');
        } else {
            showError('Error initializing: ' + err.message);
        }
    }
});

// Handle start button click
document.getElementById('startScan').addEventListener('click', async () => {
    document.getElementById('error-message').style.display = 'none';
    document.getElementById('startScan').style.display = 'none';
    await startVideo();
});

// Clean up when leaving page
window.addEventListener('beforeunload', () => {
    stopVideo();
});
</script>
@endpush 