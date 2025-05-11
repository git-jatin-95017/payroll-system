@extends('layouts.kiosk')

@section('content')
<div class="container h-100 d-flex flex-column justify-content-center align-items-center">
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    <div class="text-center mb-4">
        <h1 class="fs-5 fw-semibold mb-1">Face Recognition</h1>
        <p class="text-sm text-gray">Scan your face to verify identity</p>
    </div>
    <div class="face-scan-container mb-4">
        <div class="scan-frame">
            <video id="video" width="400" height="350" autoplay playsinline></video>
            <canvas id="canvas" width="400" height="350" style="display:none;"></canvas>
        </div>
        <div id="loading" class="loading-overlay" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button class="btn btn-verify mb-3" id="startScan">
            Start Camera
        </button>
        <a href="{{ route('kiosk.back') }}" class="back-btn text-center d-flex align-items-center justify-content-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            Back
        </a>
    </div>
</div>
@endsection
@push('styles')
<style>
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
let faceDetected = false;
let faceDetectionCount = 0;
const REQUIRED_DETECTIONS = 5;
const MIN_CONFIDENCE = 0.70;
let lastDetectionTime = 0;
const MIN_DETECTION_TIME = 500;

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
                width: { ideal: 1280 },
                height: { ideal: 720 },
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
        const faceGuide = document.querySelector('.face-guide');
        const faceStatus = document.querySelector('.face-status');
        
        if (predictions.length > 0) {
            const face = predictions[0];
            const confidence = face.probability[0];
            
            // Only consider high confidence detections
            if (confidence > MIN_CONFIDENCE) {
                // Update last detection time if this is the first detection
                if (faceDetectionCount === 0) {
                    lastDetectionTime = Date.now();
                }
                
                faceDetectionCount++;
                faceGuide.classList.add('face-detected');
                
                // Calculate time since first detection
                const detectionDuration = Date.now() - lastDetectionTime;
                const remainingTime = Math.max(0, MIN_DETECTION_TIME - detectionDuration);
                
                if (remainingTime > 0) {
                    faceStatus.textContent = `Face detected (${Math.round(confidence * 100)}%) - Hold still for ${Math.ceil(remainingTime/1000)}s...`;
                } else if (faceDetectionCount >= REQUIRED_DETECTIONS) {
                    // Face has been consistently detected for required time and count
                    faceStatus.textContent = "Capturing...";
                    await captureAndVerifyFace(face);
                } else {
                    faceStatus.textContent = `Face detected (${Math.round(confidence * 100)}%) - Keep holding still...`;
                }
            } else {
                console.log('Low confidence detection:', confidence);
                resetFaceDetection();
            }
        } else {
            console.log('No face detected in frame');
            resetFaceDetection();
        }
    } catch (err) {
        console.error('Face detection error:', err);
        resetFaceDetection();
    }
}

function resetFaceDetection() {
    faceDetectionCount = 0;
    lastDetectionTime = 0;
    const faceGuide = document.querySelector('.face-guide');
    const faceStatus = document.querySelector('.face-status');
    faceGuide.classList.remove('face-detected');
    faceStatus.textContent = "Position your face in the frame";
}

async function captureAndVerifyFace(face) {
    try {
        // Add a small delay before capture to ensure stability
        await new Promise(resolve => setTimeout(resolve, 300));
        
        // Draw video frame to canvas with consistent dimensions
        canvas.width = 640;
        canvas.height = 480;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Calculate face region with larger padding
        const faceWidth = Math.round(face.bottomRight[0] - face.topLeft[0]);
        const faceHeight = Math.round(face.bottomRight[1] - face.topLeft[1]);
        
        // Use a percentage of face size for padding (50% of face width)
        const padding = Math.max(60, Math.round(faceWidth * 0.5));
        
        // Calculate crop region ensuring we get the full face
        const x = Math.max(0, Math.round(face.topLeft[0]) - padding);
        const y = Math.max(0, Math.round(face.topLeft[1]) - padding);
        const width = Math.min(canvas.width - x, Math.round(face.bottomRight[0] - face.topLeft[0]) + (padding * 2));
        const height = Math.min(canvas.height - y, Math.round(face.bottomRight[1] - face.topLeft[1]) + (padding * 2));

        // Log face detection and crop details
        console.log('Face detection details:', {
            faceWidth,
            faceHeight,
            padding,
            cropRegion: { x, y, width, height },
            confidence: face.probability[0]
        });

        // Verify face is still in frame after capture
        if (width < 120 || height < 120) { // Increased minimum size
            console.warn('Face region too small:', { width, height });
            throw new Error('Please move closer to the camera');
        }

        // Create temporary canvas with consistent dimensions
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = width;
        tempCanvas.height = height;
        const tempContext = tempCanvas.getContext('2d');
        
        // Draw face region with consistent quality
        tempContext.drawImage(canvas, x, y, width, height, 0, 0, width, height);

        // Try multiple times to detect face in cropped image
        let verifyPredictions = null;
        let attempts = 0;
        const maxAttempts = 3;

        while (attempts < maxAttempts) {
            verifyPredictions = await model.estimateFaces(tempCanvas, false);
            if (verifyPredictions.length > 0) {
                console.log('Face verified in cropped image on attempt', attempts + 1);
                break;
            }
            attempts++;
            if (attempts < maxAttempts) {
                await new Promise(resolve => setTimeout(resolve, 100));
            }
        }

        if (!verifyPredictions || verifyPredictions.length === 0) {
            console.warn('Face verification failed after', maxAttempts, 'attempts');
            throw new Error('Please try again - make sure your face is clearly visible');
        }

        // Extract features before converting to base64
        const features = await extractFaceFeatures(tempCanvas);
        
        // Verify features before sending
        if (!Array.isArray(features) || features.length !== 12) {
            console.error('Invalid features before sending:', {
                isArray: Array.isArray(features),
                length: features ? features.length : 0,
                features: features
            });
            throw new Error('Face feature extraction failed - please try again');
        }

        // Convert to base64 with consistent quality
        const imageData = tempCanvas.toDataURL('image/jpeg', 0.7);
        
        // Log capture details for debugging
        console.log('Face capture successful:', {
            width,
            height,
            confidence: face.probability[0],
            featureCount: features.length,
            detectionCount: faceDetectionCount,
            detectionDuration: Date.now() - lastDetectionTime,
            verificationAttempts: attempts + 1,
            features: features // Log actual features
        });

        // Stop detection and video
        stopVideo();
        
        // Show loading state
        document.getElementById('loading').style.display = 'flex';
        
        try {
            // Prepare request data
            const requestData = {
                face_data: imageData,
                face_features: features
            };

            // Log request data for debugging
            console.log('Sending verification request:', {
                featureCount: requestData.face_features.length,
                features: requestData.face_features
            });

            // Send to server for verification
            const response = await fetch('{{ route("kiosk.verify-face") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            });

            const data = await response.json();
            
            // Log response for debugging
            console.log('Verification response:', data);
            
            // Hide loading state
            document.getElementById('loading').style.display = 'none';
            
            if (data.success) {
                window.location.href = '{{ route("kiosk.pin-verification") }}';
            } else {
                showError(data.message || 'Face verification failed');
                document.getElementById('startScan').style.display = 'block';
            }
        } catch (err) {
            console.error('Verification request error:', err);
            document.getElementById('loading').style.display = 'none';
            throw err;
        }
    } catch (err) {
        console.error('Face capture error:', err);
        showError('Error capturing face: ' + err.message);
        document.getElementById('startScan').style.display = 'block';
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
        
        // Extract and normalize landmarks (12 features total - 6 landmarks x 2 coordinates)
        const features = [];
        
        // Log raw landmarks for debugging
        console.log('Raw face landmarks:', face.landmarks);
        
        // Add all landmarks with normalized coordinates
        for (let i = 0; i < 6; i++) {
            if (face.landmarks[i]) {
                // Normalize x and y coordinates separately
                const x = face.landmarks[i][0] / faceCanvas.width;
                const y = face.landmarks[i][1] / faceCanvas.height;
                features.push(x, y);
                
                // Log each landmark for debugging
                console.log(`Landmark ${i}:`, {
                    raw: face.landmarks[i],
                    normalized: [x, y]
                });
            } else {
                console.warn(`Missing landmark ${i}`);
            }
        }

        // Verify we have exactly 12 features
        if (features.length !== 12) {
            console.error('Invalid feature count:', {
                expected: 12,
                actual: features.length,
                features: features
            });
            throw new Error('Face feature extraction failed - invalid feature count');
        }

        // Log feature extraction for debugging
        console.log('Extracted features:', {
            count: features.length,
            confidence: face.probability[0],
            landmarks: face.landmarks.length,
            features: features
        });
        
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