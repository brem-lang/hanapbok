<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $viewData = $field->getViewData();
        $statePath = $viewData['statePath'];
        $existingDocuments = $viewData['existingDocuments'] ?? [];
        $currentState = $viewData['state'] ?? [];
    @endphp

    <div 
        x-data="documentScanner({
            statePath: @js($statePath),
            existingDocuments: @js($existingDocuments),
            currentState: @js($currentState)
        })"
        x-init="init()"
        class="space-y-4">
        
        {{-- Existing Documents Section --}}
        <div x-show="existingDocuments.length > 0" class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
            <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Existing Document</h3>
            <div class="grid grid-cols-3 gap-2">
                <template x-for="(document, index) in existingDocuments" :key="index">
                    <div class="bg-white dark:bg-gray-700 rounded-md border border-gray-300 dark:border-gray-600 p-2 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate" :title="document.name || document.path" x-text="document.name || getDocumentName(document.path || document)"></p>
                            </div>
                            <div class="flex items-center gap-2 ml-2">
                                <a 
                                    :href="getDocumentUrl(document.path || document)" 
                                    target="_blank"
                                    class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900 rounded transition-colors"
                                    title="View document">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <button 
                                    type="button"
                                    @click="editExistingDocumentName(index)"
                                    class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900 rounded transition-colors"
                                    title="Edit name">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button 
                                    type="button"
                                    @click="deleteExistingDocument(index)"
                                    class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 rounded transition-colors"
                                    title="Delete document">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Scanner Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Document Scanner</h3>
                <button 
                    type="button"
                    @click="toggleScanner()"
                    x-text="isScanning ? 'Stop Scanner' : 'Start Scanner'"
                    class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                </button>
            </div>

            {{-- Camera Preview --}}
            <div x-show="isScanning" class="mb-4">
                <div class="relative bg-black rounded-lg overflow-hidden" style="max-height: 400px;">
                    <video 
                        x-ref="video"
                        autoplay
                        playsinline
                        class="w-full h-auto"
                        style="max-height: 400px; object-fit: contain;">
                    </video>
                    <canvas x-ref="canvas" class="hidden"></canvas>
                </div>
                <div class="mt-4 flex gap-2 justify-center" style="margin-top:10px;">
                    <x-filament::button 
                        type="button"
                        @click="captureImage()"
                        color="success"
                        size="lg"
                        icon="heroicon-o-camera">
                        Capture Document
                    </x-filament::button>
                </div>
            </div>

            {{-- Captured Images Cards --}}
            <div x-show="capturedImages.length > 0" class="mt-3">
                <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Captured Documents (<span x-text="capturedImages.length"></span>)</h4>
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="(image, index) in capturedImages" :key="index">
                        <div class="bg-white dark:bg-gray-700 rounded-md border border-gray-300 dark:border-gray-600 p-2 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate" :title="image.name" x-text="image.name"></p>
                                </div>
                                <div class="flex items-center gap-2 ml-2">
                                    <button 
                                        type="button"
                                        @click="viewCapturedImage(index)"
                                        class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900 rounded transition-colors"
                                        title="View document">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button"
                                        @click="editImageName(index)"
                                        class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900 rounded transition-colors"
                                        title="Edit name">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button"
                                        @click="removeCapturedImage(index)"
                                        class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900 rounded transition-colors"
                                        title="Delete">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Documents will be saved when you click "Save Changes"
                </p>
            </div>
            
            {{-- Image Preview Modal --}}
            <div x-show="showImagePreview" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
                 @click.self="closeImagePreview()">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 max-w-4xl w-full mx-4 shadow-xl relative"
                     @click.stop>
                    <button 
                        type="button"
                        @click="closeImagePreview()"
                        class="absolute top-2 right-2 bg-gray-800 text-white rounded-full p-2 hover:bg-gray-700 transition-colors"
                        title="Close">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="mt-2">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2" x-text="previewImageName"></h3>
                        <div class="bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden">
                            <img :src="previewImageData" alt="Document preview" class="w-full h-auto max-h-[70vh] object-contain">
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Name Input Modal --}}
            <div x-show="showNameModal" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                 @click.self="cancelNameInput()">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 shadow-xl"
                     @click.stop>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Name Your Document</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Document Name
                        </label>
                        <input 
                            type="text"
                            x-model="tempImageName"
                            @keyup.enter="confirmImageName()"
                            @keyup.escape="cancelNameInput()"
                            placeholder="e.g., Business Permit, ID Back, etc."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                            x-ref="nameInput">
                    </div>
                    <div class="flex gap-2 justify-end">
                        <button 
                            type="button"
                            @click="cancelNameInput()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button 
                            type="button"
                            @click="confirmImageName()"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden input bound to Filament state --}}
        <input 
            type="hidden" 
            wire:model="{{ $statePath }}"
            x-ref="stateInput"
            :value="JSON.stringify(getFinalState())">
    </div>

    @push('scripts')
        <script>
            function documentScanner({ statePath, existingDocuments, currentState }) {
                return {
                    statePath: statePath,
                    existingDocuments: existingDocuments || [],
                    documentsToDelete: [],
                    capturedImages: [],
                    scannedImage: null,
                    isScanning: false,
                    stream: null,
                    videoElement: null,
                    canvasElement: null,
                    showNameModal: false,
                    tempImageName: '',
                    tempImageData: null,
                    editingImageIndex: null,
                    editingExistingDocument: false,
                    showImagePreview: false,
                    previewImageData: null,
                    previewImageName: '',

                    init() {
                        this.$nextTick(() => {
                            this.videoElement = this.$refs.video;
                            this.canvasElement = this.$refs.canvas;
                            
                            // Initialize captured images from current state
                            if (Array.isArray(currentState) && currentState.length > 0) {
                                currentState.forEach(item => {
                                    if (typeof item === 'object' && item.data && item.name) {
                                        // If it's already an object with name and data (base64)
                                        this.capturedImages.push(item);
                                    } else if (typeof item === 'string' && item.startsWith('data:image/')) {
                                        // Legacy: base64 string without name
                                        this.capturedImages.push({
                                            name: 'Document ' + (this.capturedImages.length + 1),
                                            data: item
                                        });
                                    }
                                    // Skip items with 'path' - those are existing documents, not captured images
                                });
                            } else if (currentState && typeof currentState === 'string' && currentState.startsWith('data:image/')) {
                                // Single base64 image (legacy)
                                this.capturedImages.push({
                                    name: 'Document 1',
                                    data: currentState
                                });
                            }
                            
                            // Normalize existing documents to object format
                            if (Array.isArray(this.existingDocuments)) {
                                this.existingDocuments = this.existingDocuments.map(doc => {
                                    if (typeof doc === 'object' && doc.path) {
                                        return doc;
                                    } else if (typeof doc === 'string') {
                                        return {
                                            name: this.getDocumentName(doc),
                                            path: doc
                                        };
                                    }
                                    return doc;
                                });
                            }
                            
                            // Watch for changes to capturedImages and update state automatically
                            this.$watch('capturedImages', () => {
                                this.updateFilamentState();
                            }, { deep: true });
                            
                            // Listen for form submission to stop scanner
                            const form = this.$el.closest('form');
                            if (form) {
                                // Standard form submit
                                form.addEventListener('submit', () => {
                                    this.stopScanner();
                                });
                                
                                // Livewire form submission
                                form.addEventListener('livewire:submit', () => {
                                    this.stopScanner();
                                });
                                
                                // Filament form submission - detect Save Changes button click
                                const submitButtons = form.querySelectorAll('button[type="submit"], button[wire\\:click*="save"]');
                                submitButtons.forEach(button => {
                                    button.addEventListener('click', () => {
                                        // Small delay to ensure form submission starts
                                        setTimeout(() => {
                                            this.stopScanner();
                                        }, 100);
                                    });
                                });
                            }
                            
                            // Stop scanner when Livewire component updates
                            document.addEventListener('livewire:before-update', () => {
                                this.stopScanner();
                            });
                            
                            // Stop scanner on page unload
                            window.addEventListener('beforeunload', () => {
                                this.stopScanner();
                            });
                        });
                    },
                    
                    destroy() {
                        // Cleanup: stop scanner when component is destroyed
                        this.stopScanner();
                    },

                    getDocumentUrl(path) {
                        // Remove leading slash if present
                        const cleanPath = path.replace(/^\//, '');
                        // Files are stored in public/id-photo, accessible via /id-photo/
                        return '/id-photo/' + cleanPath;
                    },

                    getDocumentName(path) {
                        // Extract filename from path
                        if (typeof path === 'string') {
                            const parts = path.split('/');
                            return parts[parts.length - 1] || path;
                        }
                        return path;
                    },

                    getFinalState() {
                        const state = [];
                        
                        // Add existing documents (excluding deleted ones) in object format
                        this.existingDocuments.forEach(doc => {
                            const docPath = typeof doc === 'object' ? doc.path : doc;
                            if (!this.documentsToDelete.includes(docPath) && !this.documentsToDelete.includes(doc)) {
                                // Ensure it's in object format with name and path
                                if (typeof doc === 'object' && doc.path) {
                                    state.push({
                                        name: doc.name || this.getDocumentName(doc.path),
                                        path: doc.path
                                    });
                                } else {
                                    // Legacy format - convert to object
                                    state.push({
                                        name: this.getDocumentName(doc),
                                        path: doc
                                    });
                                }
                            }
                        });
                        
                        // Add captured images in object format with name and data
                        this.capturedImages.forEach(image => {
                            state.push({
                                name: image.name,
                                data: image.data
                            });
                        });
                        
                        return state;
                    },

                    async toggleScanner() {
                        if (this.isScanning) {
                            this.stopScanner();
                        } else {
                            await this.startScanner();
                        }
                    },

                    async startScanner() {
                        try {
                            let constraints = { 
                                video: { 
                                    facingMode: 'environment',
                                    width: { ideal: 1280 },
                                    height: { ideal: 720 }
                                } 
                            };
                            
                            try {
                                this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                            } catch (backCameraError) {
                                console.log('Back camera not available, trying any camera...');
                                constraints = { 
                                    video: { 
                                        width: { ideal: 1280 },
                                        height: { ideal: 720 }
                                    } 
                                };
                                this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                            }
                            
                            if (this.videoElement) {
                                this.videoElement.srcObject = this.stream;
                                this.isScanning = true;
                                
                                this.videoElement.onloadedmetadata = () => {
                                    this.videoElement.play().catch(err => {
                                        console.error('Error playing video:', err);
                                    });
                                };
                            } else {
                                console.error('Video element not found');
                                alert('Scanner initialization error. Please refresh the page.');
                            }
                        } catch (error) {
                            console.error('Error accessing camera:', error);
                            let errorMessage = 'Unable to access camera. ';
                            
                            if (error.name === 'NotAllowedError') {
                                errorMessage += 'Please allow camera access in your browser settings.';
                            } else if (error.name === 'NotFoundError') {
                                errorMessage += 'No camera found on your device.';
                            } else if (error.name === 'NotReadableError') {
                                errorMessage += 'Camera is being used by another application.';
                            } else {
                                errorMessage += 'Please check your camera permissions and try again.';
                            }
                            
                            alert(errorMessage);
                            this.isScanning = false;
                        }
                    },

                    stopScanner() {
                        if (this.stream) {
                            this.stream.getTracks().forEach(track => track.stop());
                            this.stream = null;
                        }
                        if (this.videoElement) {
                            this.videoElement.srcObject = null;
                        }
                        this.isScanning = false;
                    },

                    captureImage() {
                        if (!this.videoElement || !this.canvasElement) {
                            alert('Camera not ready. Please wait for camera to start.');
                            return;
                        }

                        const video = this.videoElement;
                        const canvas = this.canvasElement;
                        
                        if (video.readyState !== video.HAVE_ENOUGH_DATA) {
                            alert('Camera is not ready yet. Please wait a moment.');
                            return;
                        }
                        
                        try {
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                            
                            const imageData = canvas.toDataURL('image/jpeg', 0.9);
                            
                            // Store the captured image data temporarily
                            this.tempImageData = imageData;
                            this.tempImageName = 'Document ' + (this.capturedImages.length + 1);
                            this.editingImageIndex = null;
                            
                            // Show name input modal
                            this.showNameModal = true;
                            
                            // Focus on input after modal appears
                            this.$nextTick(() => {
                                if (this.$refs.nameInput) {
                                    this.$refs.nameInput.focus();
                                    this.$refs.nameInput.select();
                                }
                            });
                            
                            const button = event.target;
                            const originalText = button.textContent;
                            button.textContent = '✓ Captured!';
                            button.classList.add('bg-green-500');
                            setTimeout(() => {
                                button.textContent = originalText;
                                button.classList.remove('bg-green-500');
                            }, 1000);
                        } catch (error) {
                            console.error('Error capturing image:', error);
                            alert('Error capturing image. Please try again.');
                        }
                    },

                    confirmImageName() {
                        if (!this.tempImageName || this.tempImageName.trim() === '') {
                            alert('Please enter a name for the document.');
                            return;
                        }
                        
                        if (this.editingExistingDocument && this.editingImageIndex !== null) {
                            // Editing existing document name
                            const doc = this.existingDocuments[this.editingImageIndex];
                            if (typeof doc === 'object') {
                                doc.name = this.tempImageName.trim();
                            } else {
                                // Convert to object format
                                this.existingDocuments[this.editingImageIndex] = {
                                    name: this.tempImageName.trim(),
                                    path: doc
                                };
                            }
                        } else if (this.editingImageIndex !== null) {
                            // Editing captured image name
                            this.capturedImages[this.editingImageIndex].name = this.tempImageName.trim();
                        } else {
                            // Adding new image
                            this.capturedImages.push({
                                name: this.tempImageName.trim(),
                                data: this.tempImageData
                            });
                        }
                        
                        // Reset modal state
                        this.showNameModal = false;
                        this.tempImageName = '';
                        this.tempImageData = null;
                        this.editingImageIndex = null;
                        this.editingExistingDocument = false;
                        
                        // Update Filament state
                        this.updateFilamentState();
                    },

                    cancelNameInput() {
                        this.showNameModal = false;
                        this.tempImageName = '';
                        this.tempImageData = null;
                        this.editingImageIndex = null;
                        this.editingExistingDocument = false;
                    },

                    editImageName(index) {
                        const image = this.capturedImages[index];
                        this.tempImageName = image.name;
                        this.tempImageData = image.data;
                        this.editingImageIndex = index;
                        this.editingExistingDocument = false;
                        this.showNameModal = true;
                        
                        // Focus on input after modal appears
                        this.$nextTick(() => {
                            if (this.$refs.nameInput) {
                                this.$refs.nameInput.focus();
                                this.$refs.nameInput.select();
                            }
                        });
                    },

                    removeCapturedImage(index) {
                        this.capturedImages.splice(index, 1);
                        this.updateFilamentState();
                    },

                    viewCapturedImage(index) {
                        const image = this.capturedImages[index];
                        this.previewImageData = image.data;
                        this.previewImageName = image.name;
                        this.showImagePreview = true;
                    },

                    closeImagePreview() {
                        this.showImagePreview = false;
                        this.previewImageData = null;
                        this.previewImageName = '';
                    },

                    editExistingDocumentName(index) {
                        const doc = this.existingDocuments[index];
                        const docName = typeof doc === 'object' ? doc.name : this.getDocumentName(doc.path || doc);
                        this.tempImageName = docName;
                        this.tempImageData = null;
                        this.editingImageIndex = index;
                        this.editingExistingDocument = true;
                        this.showNameModal = true;
                        
                        // Focus on input after modal appears
                        this.$nextTick(() => {
                            if (this.$refs.nameInput) {
                                this.$refs.nameInput.focus();
                                this.$refs.nameInput.select();
                            }
                        });
                    },

                    deleteExistingDocument(index) {
                        const doc = this.existingDocuments[index];
                        // Get the path for deletion tracking
                        const docPath = typeof doc === 'object' ? doc.path : doc;
                        this.documentsToDelete.push(docPath);
                        this.existingDocuments.splice(index, 1);
                        this.updateFilamentState();
                    },

                    updateFilamentState() {
                        const finalState = this.getFinalState();
                        
                        // Update via $wire if available (Alpine.js in Filament context)
                        if (this.$wire) {
                            this.$wire.set(this.statePath, finalState);
                        } else {
                            // Fallback: find the form's Livewire component and update directly
                            const form = this.$el.closest('form');
                            if (form) {
                                const wireId = form.getAttribute('wire:id') || 
                                             form.querySelector('[wire\\:id]')?.getAttribute('wire:id') ||
                                             form.closest('[wire\\:id]')?.getAttribute('wire:id');
                                
                                if (wireId && window.Livewire) {
                                    const component = window.Livewire.find(wireId);
                                    if (component) {
                                        component.set('data.' + this.statePath, finalState);
                                    }
                                }
                            }
                            
                            // Also update hidden input
                            const stateInput = this.$refs.stateInput;
                            if (stateInput) {
                                stateInput.value = JSON.stringify(finalState);
                                stateInput.dispatchEvent(new Event('input', { bubbles: true }));
                                stateInput.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }
                    }
                };
            }
        </script>
    @endpush
</x-dynamic-component>
