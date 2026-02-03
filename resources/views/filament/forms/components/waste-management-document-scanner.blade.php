<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $viewData = $field->getViewData();
        $statePath = $viewData['statePath'];
        $existingDocuments = $viewData['existingDocuments'] ?? [];
        $currentState = $viewData['state'] ?? [];
    @endphp

    <div x-data="wasteManagementDocumentScanner({
        statePath: @js($statePath),
        existingDocuments: @js($existingDocuments),
        currentState: @js($currentState)
    })" x-init="init()" class="space-y-4">

        {{-- Existing Documents Section --}}
        <div x-show="existingDocuments.length > 0" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl p-4 sm:p-6 mb-4 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Existing Documents</h3>
                    <span
                        class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-full"
                        x-text="existingDocuments.length"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <template x-for="(document, index) in existingDocuments" :key="index">
                    <div
                        class="group relative bg-white dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600 overflow-hidden hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="p-1.5 bg-primary-100 dark:bg-primary-900/30 rounded">
                                            <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate"
                                            :title="document.name || document.path"
                                            x-text="document.name || getDocumentName(document.path || document)"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="viewExistingDocument(index)"
                                        class="p-2 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95"
                                        style="color: #2563eb !important; background-color: rgba(59, 130, 246, 0.15) !important; border: 1px solid rgba(59, 130, 246, 0.3) !important;"
                                        onmouseover="this.style.backgroundColor='rgba(59, 130, 246, 0.25)'; this.style.borderColor='rgba(59, 130, 246, 0.5)';"
                                        onmouseout="this.style.backgroundColor='rgba(59, 130, 246, 0.15)'; this.style.borderColor='rgba(59, 130, 246, 0.3)';"
                                        title="View document">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: #2563eb !important;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button type="button" @click="editExistingDocumentName(index)"
                                        class="p-2 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95"
                                        style="color: #d97706 !important; background-color: rgba(217, 119, 6, 0.15) !important; border: 1px solid rgba(217, 119, 6, 0.3) !important;"
                                        onmouseover="this.style.backgroundColor='rgba(217, 119, 6, 0.25)'; this.style.borderColor='rgba(217, 119, 6, 0.5)';"
                                        onmouseout="this.style.backgroundColor='rgba(217, 119, 6, 0.15)'; this.style.borderColor='rgba(217, 119, 6, 0.3)';"
                                        title="Edit name">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: #d97706 !important;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button type="button" @click="deleteExistingDocument(index)"
                                        class="p-2 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95"
                                        style="color: #dc2626 !important; background-color: rgba(220, 38, 38, 0.15) !important; border: 1px solid rgba(220, 38, 38, 0.3) !important;"
                                        onmouseover="this.style.backgroundColor='rgba(220, 38, 38, 0.25)'; this.style.borderColor='rgba(220, 38, 38, 0.5)';"
                                        onmouseout="this.style.backgroundColor='rgba(220, 38, 38, 0.15)'; this.style.borderColor='rgba(220, 38, 38, 0.3)';"
                                        title="Delete document">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="color: #dc2626 !important;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Scanner Section --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border-2 border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Waste Management Documents Scanner</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Capture documents using your camera
                        </p>
                    </div>
                </div>
                <button type="button" @click="toggleScanner()" x-show="capturedImages.length === 0 && !isScanning"
                    class="px-6 py-3 text-sm font-semibold text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 flex items-center gap-2"
                    style="background: linear-gradient(to right, #2563eb, #1d4ed8) !important;"
                    onmouseover="this.style.background='linear-gradient(to right, #1d4ed8, #1e40af)'"
                    onmouseout="this.style.background='linear-gradient(to right, #2563eb, #1d4ed8)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: white !important;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                    Start Scanner
                </button>
                <button type="button" @click="toggleScanner()" x-show="isScanning && capturedImages.length > 0"
                    class="px-6 py-3 text-sm font-semibold text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95 flex items-center gap-2"
                    style="background: linear-gradient(to right, #dc2626, #b91c1c) !important;"
                    onmouseover="this.style.background='linear-gradient(to right, #b91c1c, #991b1b)'"
                    onmouseout="this.style.background='linear-gradient(to right, #dc2626, #b91c1c)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color: white !important;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    Stop Scanner
                </button>
            </div>

            {{-- Fullscreen Camera Preview Modal --}}
            <div x-show="isScanning" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black flex flex-col"
                style="z-index: 99999 !important;">
                {{-- Video Preview - Full Screen --}}
                <div class="absolute inset-0 flex items-center justify-center bg-black overflow-hidden">
                    <video x-ref="video" autoplay playsinline class="w-full h-full object-contain">
                    </video>
                    <canvas x-ref="canvas" class="hidden"></canvas>
                </div>

                {{-- Top Bar with Close Button - Overlay on Video --}}
                <div class="relative z-[100] p-2 sm:p-4 flex items-start"
                    style="padding-top: calc(env(safe-area-inset-top, 0px) + 0.5rem); padding-left: calc(env(safe-area-inset-left, 0px) + 0.5rem); padding-right: calc(env(safe-area-inset-right, 0px) + 0.5rem);">
                    <button type="button" @click="stopScanner()"
                        class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white rounded-lg px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base font-semibold transition-colors shadow-2xl flex items-center gap-1.5 sm:gap-2 touch-manipulation min-h-[44px] sm:min-h-[48px] border-2 border-white/50 backdrop-blur-md"
                        style="background-color: #dc2626 !important;" title="Close Scanner">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="hidden sm:inline">Close</span>
                    </button>
                </div>

                {{-- Spacer to push capture button down --}}
                <div class="flex-1"></div>

                {{-- Bottom Bar with Capture Button - Overlay on Video --}}
                <div class="relative z-[100] p-3 sm:p-4 md:p-6 flex justify-center items-center"
                    style="padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 0.75rem); padding-left: calc(env(safe-area-inset-left, 0px) + 0.75rem); padding-right: calc(env(safe-area-inset-right, 0px) + 0.75rem);">
                    <button type="button" @click="captureImage()"
                        class="px-6 py-3 sm:px-8 sm:py-4 md:px-10 md:py-5 text-base sm:text-lg md:text-xl font-bold text-white rounded-full hover:opacity-90 active:opacity-80 transition-opacity shadow-2xl flex items-center gap-2 sm:gap-3 min-w-[160px] sm:min-w-[180px] md:min-w-[200px] min-h-[48px] sm:min-h-[56px] md:min-h-[64px] justify-center touch-manipulation border-2 border-white backdrop-blur-md"
                        style="background-color: #16a34a !important; border-width: 3px;" title="Capture Document">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="hidden sm:inline">Capture Document</span>
                        <span class="sm:hidden">Capture</span>
                    </button>
                </div>
            </div>

            {{-- Captured Images Cards --}}
            <div x-show="capturedImages.length > 0" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0" 
                class="mt-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-900 rounded-xl p-4 sm:p-6 border-2 border-green-200 dark:border-green-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Captured Documents</h4>
                        <span
                            class="px-2.5 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-bold rounded-full"
                            x-text="capturedImages.length"></span>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <template x-for="(image, index) in capturedImages" :key="index">
                        <div
                            class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-lg border-2 border-gray-200 dark:border-gray-600 overflow-hidden hover:border-green-400 dark:hover:border-green-500 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="p-1.5 bg-green-100 dark:bg-green-900/30 rounded">
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate"
                                                :title="image.name" x-text="image.name"></p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button type="button" @click="viewCapturedImage(index)"
                                            class="p-2 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95"
                                            style="color: #2563eb !important; background-color: rgba(59, 130, 246, 0.1) !important;"
                                            onmouseover="this.style.backgroundColor='rgba(59, 130, 246, 0.2)'"
                                            onmouseout="this.style.backgroundColor='rgba(59, 130, 246, 0.1)'"
                                            title="View document">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="color: #2563eb !important;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button type="button" @click="editImageName(index)"
                                            class="p-2 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95"
                                            style="color: #d97706 !important; background-color: rgba(217, 119, 6, 0.1) !important;"
                                            onmouseover="this.style.backgroundColor='rgba(217, 119, 6, 0.2)'"
                                            onmouseout="this.style.backgroundColor='rgba(217, 119, 6, 0.1)'"
                                            title="Edit name">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="color: #d97706 !important;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button type="button" @click="removeCapturedImage(index)"
                                            class="p-2 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95"
                                            style="color: #dc2626 !important; background-color: rgba(220, 38, 38, 0.1) !important;"
                                            onmouseover="this.style.backgroundColor='rgba(220, 38, 38, 0.2)'"
                                            onmouseout="this.style.backgroundColor='rgba(220, 38, 38, 0.1)'"
                                            title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" style="color: #dc2626 !important;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div
                    class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-xs font-medium text-blue-800 dark:text-blue-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Documents will be saved when you click "Save Changes"
                    </p>
                </div>
            </div>

            {{-- Image Preview Modal --}}
            <div x-show="showImagePreview" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm"
                style="z-index: 10000;" @click.self="closeImagePreview()">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-5xl w-full mx-4 shadow-2xl relative transform transition-all duration-300"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    @click.stop>
                    <button type="button" @click="closeImagePreview()"
                        class="absolute top-4 right-4 text-white rounded-full p-2.5 transition-all duration-200 hover:scale-110 active:scale-95 shadow-lg"
                        style="background-color: #1f2937 !important;"
                        onmouseover="this.style.backgroundColor='#374151'"
                        onmouseout="this.style.backgroundColor='#1f2937'" title="Close">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="color: white !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="mt-2">
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100" x-text="previewImageName">
                            </h3>
                        </div>
                        <div
                            class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-xl overflow-hidden border-2 border-gray-200 dark:border-gray-700">
                            <img :src="previewImageData" alt="Document preview"
                                class="w-full h-auto max-h-[75vh] object-contain">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Name Input Modal --}}
            <div x-show="showNameModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 flex items-center justify-center bg-black/80 backdrop-blur-sm"
                style="z-index: 10000;" @click.self="cancelNameInput()">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all duration-300"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    @click.stop>
                    <div class="flex items-center gap-3 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Name Your Document</h3>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Document Name
                        </label>
                        <input type="text" x-model="tempImageName" @keyup.enter="confirmImageName()"
                            @keyup.escape="cancelNameInput()" placeholder="e.g., Business Permit, ID Back, etc."
                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200 text-sm font-medium"
                            x-ref="nameInput">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" @click="cancelNameInput()"
                            class="px-6 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 active:scale-95"
                            style="color: #374151 !important; background-color: #f3f4f6 !important;"
                            onmouseover="this.style.backgroundColor='#e5e7eb'"
                            onmouseout="this.style.backgroundColor='#f3f4f6'">
                            Cancel
                        </button>
                        <button type="button" @click="confirmImageName()"
                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95"
                            style="background: linear-gradient(to right, #2563eb, #1d4ed8) !important;"
                            onmouseover="this.style.background='linear-gradient(to right, #1d4ed8, #1e40af)'"
                            onmouseout="this.style.background='linear-gradient(to right, #2563eb, #1d4ed8)'">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden input bound to Filament state --}}
        <input type="hidden" wire:model="{{ $statePath }}" x-ref="stateInput"
            :value="JSON.stringify(getFinalState())">
    </div>

    @push('scripts')
        <script>
            function wasteManagementDocumentScanner({
                statePath,
                existingDocuments,
                currentState
            }) {
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
                            } else if (currentState && typeof currentState === 'string' && currentState.startsWith(
                                    'data:image/')) {
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
                            }, {
                                deep: true
                            });

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
                                const submitButtons = form.querySelectorAll(
                                    'button[type="submit"], button[wire\\:click*="save"]');
                                submitButtons.forEach(button => {
                                    button.addEventListener('click', () => {
                                        // Small delay to ensure form submission starts
                                        setTimeout(() => {
                                            this.stopScanner();
                                        }, 100);
                                    });
                                });
                            }

                            // Reload page after successful save
                            // Listen for Livewire component updates that indicate successful save
                            Livewire.hook('morph.updated', ({
                                el,
                                component
                            }) => {
                                // Check if this is a Filament form and if save was successful
                                const form = this.$el.closest('form');
                                if (form && component && component.__instance) {
                                    // Check for redirect or success indicators
                                    const hasRedirect = form.querySelector('[wire\\:loading\\.remove]');
                                    if (hasRedirect) {
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 500);
                                    }
                                }
                            });

                            // Also listen for navigation events (Filament redirects)
                            window.addEventListener('popstate', () => {
                                // If we're still on the edit page after save, reload
                                if (window.location.pathname.includes('/edit')) {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 300);
                                }
                            });

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
                                    width: {
                                        ideal: 1280
                                    },
                                    height: {
                                        ideal: 720
                                    }
                                }
                            };

                            try {
                                this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                            } catch (backCameraError) {
                                console.log('Back camera not available, trying any camera...');
                                constraints = {
                                    video: {
                                        width: {
                                            ideal: 1280
                                        },
                                        height: {
                                            ideal: 720
                                        }
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

                            // Stop the camera immediately after capture
                            this.stopScanner();

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

                    viewExistingDocument(index) {
                        const doc = this.existingDocuments[index];
                        const docPath = typeof doc === 'object' ? doc.path : doc;
                        const docName = typeof doc === 'object' ? doc.name : this.getDocumentName(docPath);
                        this.previewImageData = this.getDocumentUrl(docPath);
                        this.previewImageName = docName;
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
                                stateInput.dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));
                                stateInput.dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                            }
                        }
                    }
                };
            }
        </script>
    @endpush
</x-dynamic-component>
