<!-- Face Capture Modal -->
<div class="modal fade" id="faceCaptureModal" tabindex="-1" aria-labelledby="faceCaptureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faceCaptureModalLabel">Update Face Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Position your face in the center and ensure good lighting for best results.
                </div>

                <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                <div class="face-capture-container mb-4">
                    <div class="scan-frame">
                        <video id="videoCapture" playsinline autoplay muted></video>
                        <canvas id="canvasCapture" style="display:none;"></canvas>
                        <div class="face-guide-overlay">
                            <div class="face-guide"></div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-primary btn-lg" id="startCapture">
                        <i class="fas fa-camera me-2"></i>Start Camera
                    </button>
                    <button type="button" class="btn btn-success btn-lg" id="takePhoto" style="display: none;">
                        <i class="fas fa-camera me-2"></i>Capture Photo
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg ms-2" id="switchCamera" style="display: none;">
                        <i class="fas fa-sync me-2"></i>Switch Camera
                    </button>
                </div>

                <div class="mt-4">
                    <h6 class="mb-3">Captured Faces</h6>
                    <div class="captured-faces row g-3" id="capturedFaces"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveFaces">Save Face Data</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
}

.modal-content {
    max-width: 100%;
    overflow: hidden;
}

.face-capture-container {
    position: relative;
    width: 100%;
    max-width: 640px;
    margin: 0 auto;
    border: 2px solid #6f42c1;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 4/3;
}

.scan-frame {
    position: relative;
    width: 100%;
    height: 100%;
    background-color: #000;
}

.scan-frame video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.face-guide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.face-guide {
    width: 200px;
    height: 200px;
    border: 2px dashed rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    position: relative;
}

.face-guide::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 180px;
    height: 180px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
}

/* Mobile-specific styles */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }

    .face-capture-container {
        border-width: 1px;
        max-width: 100%;
    }

    .face-guide {
        width: 150px;
        height: 150px;
    }

    .face-guide::before {
        width: 130px;
        height: 130px;
    }

    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }

    .captured-faces .col-md-4 {
        width: 50%;
    }
}

/* iPad-specific styles */
@media (min-width: 769px) and (max-width: 1024px) {
    .modal-dialog {
        max-width: calc(100% - 2rem);
    }

    .face-capture-container {
        max-width: 100%;
    }

    .captured-faces .col-md-4 {
        width: 33.333%;
    }
}

.captured-faces img {
    width: 100%;
    max-width: 200px;
    height: auto;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    border: 1px solid #dee2e6;
}

.btn-remove-photo {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    padding: 0;
    border-radius: 50%;
    background: #dc3545;
    color: white;
    border: none;
    font-size: 16px;
    line-height: 24px;
    z-index: 1;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-remove-photo:hover {
    background: #c82333;
}

#error-message {
    margin-bottom: 1rem;
}

.alert-info {
    background-color: #cce5ff;
    border-color: #b8daff;
}

.btn-lg {
    min-width: 140px;
}

/* Ensure the start button is clickable */
#startCapture {
    cursor: pointer !important;
    pointer-events: auto !important;
    opacity: 1 !important;
}

/* Add a hover effect to show it's clickable */
#startCapture:hover {
    background-color: #0056b3 !important;
}

/* Ensure buttons are clickable */
#startCapture,
#takePhoto,
#switchCamera,
#saveFaces {
    cursor: pointer !important;
    pointer-events: auto !important;
    user-select: none !important;
    -webkit-user-select: none !important;
}

/* Add hover effects */
#startCapture:hover,
#takePhoto:hover,
#switchCamera:hover,
#saveFaces:hover {
    opacity: 0.9;
}

/* Ensure the modal is properly layered */
#faceCaptureModal {
    z-index: 1050;
}

.modal-backdrop {
    z-index: 1040;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>

<script>
// Debug flag
const DEBUG = true;

function debug(message) {
    if (DEBUG) {
        console.log(`[Face Capture Debug] ${message}`);
    }
}

let model = null;
let currentStream = null;
let currentFacingMode = 'user';
let faceDetectionInterval = null;

// Initialize BlazeFace model
async function initializeModel() {
    debug('Initializing BlazeFace model...');
    try {
        if (!model) {
            model = await blazeface.load();
            debug('BlazeFace model loaded successfully');
        }
        return true;
    } catch (error) {
        console.error('Error loading BlazeFace model:', error);
        showError('Failed to initialize face detection model: ' + error.message);
        return false;
    }
}

function showError(message) {
    const errorElement = document.getElementById('error-message');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function hideError() {
    const errorElement = document.getElementById('error-message');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

// Stop current camera stream
function stopCurrentStream() {
    if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop());
        currentStream = null;
    }
    if (faceDetectionInterval) {
        clearInterval(faceDetectionInterval);
        faceDetectionInterval = null;
    }
}

// Face detection function
async function startFaceDetection() {
    if (!model) return;
    
    const video = document.getElementById('videoCapture');
    const canvas = document.getElementById('canvasCapture');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const context = canvas.getContext('2d');
    
    // Stop any existing interval
    if (faceDetectionInterval) {
        clearInterval(faceDetectionInterval);
    }
    
    faceDetectionInterval = setInterval(async () => {
        try {
            // Draw current frame
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Detect faces
            const predictions = await model.estimateFaces(video, false);
            
            if (predictions.length > 0) {
                document.getElementById('takePhoto').disabled = false;
            } else {
                document.getElementById('takePhoto').disabled = true;
            }
        } catch (error) {
            console.error('Face detection error:', error);
        }
    }, 100);
}

// Extract face features
async function extractFaceFeatures(canvas) {
    try {
        const predictions = await model.estimateFaces(canvas, false);
        if (predictions.length === 0) {
            throw new Error('No face detected in the captured image');
        }
        
        // Normalize and extract features
        const face = predictions[0];
        const features = [
            ...face.landmarks[0], // right eye
            ...face.landmarks[1], // left eye
            ...face.landmarks[2], // nose
            ...face.landmarks[3], // mouth
            ...face.landmarks[4], // right ear
            ...face.landmarks[5]  // left ear
        ].map(coord => coord / canvas.width); // Normalize coordinates
        
        return features;
    } catch (error) {
        console.error('Feature extraction error:', error);
        throw new Error('Failed to extract face features');
    }
}

// Take photo with improved error handling
async function takePhoto() {
    debug('Taking photo...');
    const video = document.getElementById('videoCapture');
    const canvas = document.getElementById('canvasCapture');
    const context = canvas.getContext('2d');
    const capturedFacesDiv = document.getElementById('capturedFaces');

    try {
        if (!video.srcObject || !video.srcObject.active) {
            throw new Error('Camera is not active');
        }

        // Set canvas dimensions to match video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Draw video frame to canvas (handle mirroring for front camera)
        context.save();
        if (currentFacingMode === 'user') {
            context.scale(-1, 1);
            context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        } else {
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
        }
        context.restore();
        
        // Extract face features
        const features = await extractFaceFeatures(canvas);
        
        // Convert to base64 with reduced quality
        const imageData = canvas.toDataURL('image/jpeg', 0.7);
        
        // Create preview
        const previewDiv = document.createElement('div');
        previewDiv.className = 'col-md-4 mb-3';
        previewDiv.innerHTML = `
            <div class="position-relative">
                <img src="${imageData}" class="img-fluid rounded" alt="Captured face">
                <button type="button" class="btn-remove-photo" onclick="this.closest('.col-md-4').remove()">&times;</button>
            </div>
        `;
        
        // Store features with the preview
        previewDiv.dataset.features = JSON.stringify(features);
        
        capturedFacesDiv.appendChild(previewDiv);
        hideError();
        
        debug('Photo captured successfully');
    } catch (error) {
        console.error('Photo capture error:', error);
        showError('Failed to capture photo: ' + error.message);
    }
}

// Camera initialization with error handling
async function initializeCamera() {
    debug('Initializing camera...');
    try {
        // Stop any existing stream first
        stopCurrentStream();
        
        // Request camera access with current facing mode
        const constraints = {
            video: {
                facingMode: currentFacingMode,
                width: { ideal: 640 },
                height: { ideal: 480 }
            }
        };
        
        debug('Requesting camera access...');
        currentStream = await navigator.mediaDevices.getUserMedia(constraints);
        debug('Camera access granted');
        
        const videoElement = document.getElementById('videoCapture');
        if (!videoElement) {
            throw new Error('Video element not found');
        }
        
        videoElement.srcObject = currentStream;
        
        // Wait for video to be ready
        await new Promise((resolve) => {
            videoElement.onloadedmetadata = () => {
                videoElement.play().then(resolve);
            };
        });
        
        debug('Video stream started');
        
        // Show camera controls once stream is active
        const startButton = document.getElementById('startCapture');
        const takePhotoButton = document.getElementById('takePhoto');
        const switchCameraButton = document.getElementById('switchCamera');
        
        if (startButton) startButton.style.display = 'none';
        if (takePhotoButton) takePhotoButton.style.display = 'inline-block';
        if (switchCameraButton) switchCameraButton.style.display = 'inline-block';
        
        // Initialize canvas
        const canvas = document.getElementById('canvasCapture');
        if (canvas) {
            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;
        }
        
        // Start face detection
        if (await initializeModel()) {
            startFaceDetection();
        }
        
        hideError();
    } catch (error) {
        console.error('Camera initialization failed:', error);
        showError('Failed to access camera: ' + error.message);
        
        // Reset UI on error
        const startButton = document.getElementById('startCapture');
        const takePhotoButton = document.getElementById('takePhoto');
        const switchCameraButton = document.getElementById('switchCamera');
        
        if (startButton) startButton.style.display = 'inline-block';
        if (takePhotoButton) takePhotoButton.style.display = 'none';
        if (switchCameraButton) switchCameraButton.style.display = 'none';
    }
}

// Save face data
function saveFaceData() {
    try {
        const capturedFaces = document.getElementById('capturedFaces').children;
        const faceData = [];
        
        for (const face of capturedFaces) {
            const img = face.querySelector('img');
            const features = face.dataset.features;
            
            if (img && features) {
                faceData.push({
                    image: img.src,
                    features: JSON.parse(features)
                });
            }
        }
        
        if (faceData.length === 0) {
            showError('Please capture at least one face photo');
            return;
        }
        
        // Update hidden input with face data
        const faceDataInput = document.querySelector('input[name="face_data"]');
        if (!faceDataInput) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'face_data';
            document.querySelector('form').appendChild(input);
        }
        document.querySelector('input[name="face_data"]').value = JSON.stringify(faceData);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('faceCaptureModal'));
        modal.hide();
        
        hideError();
    } catch (error) {
        console.error('Error saving face data:', error);
        showError('Failed to save face data: ' + error.message);
    }
}

// Load existing face data if available
async function loadExistingFaceData() {
    const faceDataInput = document.querySelector('input[name="face_data"]');
    if (faceDataInput && faceDataInput.value) {
        try {
            const existingData = JSON.parse(faceDataInput.value);
            if (Array.isArray(existingData)) {
                const capturedFacesContainer = document.getElementById('capturedFaces');
                capturedFacesContainer.innerHTML = ''; // Clear existing faces
                
                existingData.forEach((face, index) => {
                    if (face.image && face.features) {
                        const faceElement = document.createElement('div');
                        faceElement.className = 'col-md-4 mb-3';
                        faceElement.innerHTML = `
                            <div class="position-relative">
                                <img src="${face.image}" class="img-fluid rounded" alt="Captured face ${index + 1}">
                                <button type="button" class="btn-remove-photo" onclick="this.closest('.col-md-4').remove()">&times;</button>
                            </div>
                        `;
                        faceElement.dataset.features = JSON.stringify(face.features);
                        capturedFacesContainer.appendChild(faceElement);
                    }
                });
            }
        } catch (error) {
            console.error('Error loading existing face data:', error);
            showError('Failed to load existing face data');
        }
    }
}

// Initialize event listeners
function initializeEventListeners() {
    debug('Initializing event listeners...');
    
    const modal = document.getElementById('faceCaptureModal');
    if (!modal) {
        debug('Error: Modal element not found');
        return;
    }

    const startButton = document.getElementById('startCapture');
    const takePhotoButton = document.getElementById('takePhoto');
    const switchCameraButton = document.getElementById('switchCamera');
    const saveFacesButton = document.getElementById('saveFaces');

    debug('Found buttons:', {
        startButton: !!startButton,
        takePhotoButton: !!takePhotoButton,
        switchCameraButton: !!switchCameraButton,
        saveFacesButton: !!saveFacesButton
    });

    if (startButton) {
        debug('Adding click listener to start button');
        startButton.addEventListener('click', initializeCamera);
        // Ensure the button is enabled
        startButton.disabled = false;
        startButton.style.pointerEvents = 'auto';
    }

    if (takePhotoButton) {
        takePhotoButton.addEventListener('click', takePhoto);
    }

    if (switchCameraButton) {
        switchCameraButton.addEventListener('click', async () => {
            currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
            await initializeCamera();
        });
    }

    if (saveFacesButton) {
        saveFacesButton.addEventListener('click', saveFaceData);
    }

    // Modal event listeners
    modal.addEventListener('show.bs.modal', async function (e) {
        debug('Modal show event triggered');
        hideError();
        await initializeModel();
        loadExistingFaceData();
    });

    modal.addEventListener('hide.bs.modal', function (e) {
        debug('Modal hide event triggered');
        stopCurrentStream();
    });
}

// Call initializeEventListeners when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEventListeners);
} else {
    initializeEventListeners();
}

// Ensure initialization also happens when the script loads
debug('Script loaded, initializing...');
initializeEventListeners();
</script>
@endpush 