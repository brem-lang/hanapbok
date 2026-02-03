<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\Storage;

class ValidIdDocumentScanner extends Field
{
    protected string $view = 'filament.forms.components.valid-id-document-scanner';

    protected string $disk = 'public_uploads_id';

    protected string $directory = '/';

    protected int $maxSize = 10000;

    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function directory(string $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function maxSize(int $maxSize): static
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (ValidIdDocumentScanner $component, $state) {
            // Ensure state is always an array
            if (is_string($state) && !empty($state)) {
                $decoded = json_decode($state, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Convert legacy format (array of strings) to new format (array of objects)
                    $normalized = array_map(function($item) {
                        if (is_string($item)) {
                            return [
                                'name' => basename($item),
                                'path' => $item
                            ];
                        }
                        return $item;
                    }, $decoded);
                    $component->state($normalized);
                    return;
                }
            }
            
            // Convert legacy format if needed
            if (is_array($state)) {
                $normalized = array_map(function($item) {
                    if (is_string($item)) {
                        return [
                            'name' => basename($item),
                            'path' => $item
                        ];
                    }
                    return $item;
                }, $state);
                $component->state($normalized);
            } else {
                $component->state([]);
            }
        });

        // Save base64 images to files before form submission
        $this->mutateDehydratedStateUsing(function (ValidIdDocumentScanner $component, $state) {
            if (empty($state)) {
                return [];
            }

            // Handle array of documents (existing + new)
            $documents = is_array($state) ? $state : [$state];
            $savedDocuments = [];
            $documentsToDelete = [];

            foreach ($documents as $document) {
                // Handle object format: {name: "Document Name", path: "file/path.jpg"} or {name: "Document Name", data: "base64..."}
                if (is_array($document) && isset($document['name'])) {
                    // If it has a path, it's an existing document
                    if (isset($document['path']) && !str_starts_with($document['path'], '__DELETE__')) {
                        $savedDocuments[] = [
                            'name' => $document['name'],
                            'path' => $document['path']
                        ];
                        continue;
                    }
                    // If it has data (base64), save it
                    if (isset($document['data']) && str_starts_with($document['data'], 'data:image/')) {
                        $savedPath = $component->saveBase64Image($document['data']);
                        if ($savedPath) {
                            $savedDocuments[] = [
                                'name' => $document['name'],
                                'path' => $savedPath
                            ];
                        }
                    }
                    continue;
                }

                // Handle string format (legacy support)
                if (is_string($document)) {
                    // Check if it's marked for deletion
                    if (str_starts_with($document, '__DELETE__')) {
                        $originalPath = str_replace('__DELETE__', '', $document);
                        $documentsToDelete[] = $originalPath;
                        continue;
                    }
                    
                    // If it's already a file path (existing document without name)
                    if (!str_starts_with($document, 'data:image/')) {
                        // Extract filename as name
                        $filename = basename($document);
                        $savedDocuments[] = [
                            'name' => $filename,
                            'path' => $document
                        ];
                        continue;
                    }

                    // Handle base64 image data (without name, use default)
                    if (str_starts_with($document, 'data:image/')) {
                        $savedPath = $component->saveBase64Image($document);
                        if ($savedPath) {
                            $filename = basename($savedPath);
                            $savedDocuments[] = [
                                'name' => $filename,
                                'path' => $savedPath
                            ];
                        }
                    }
                }
            }

            // Delete marked documents
            foreach ($documentsToDelete as $path) {
                try {
                    if (Storage::disk($component->getDisk())->exists($path)) {
                        Storage::disk($component->getDisk())->delete($path);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error deleting document: ' . $e->getMessage());
                }
            }

            return $savedDocuments;
        });

        $this->dehydrated(true);
        $this->live();
    }

    protected function saveBase64Image(string $base64Data): ?string
    {
        try {
            // Remove data URI prefix
            $cleanBase64 = preg_replace('#^data:image/[^;]+;base64,#i', '', $base64Data);
            $cleanBase64 = preg_replace('/\s+/', '', $cleanBase64);
            
            // Decode base64 image
            $imageData = base64_decode($cleanBase64, true);
            
            if ($imageData === false || empty($imageData)) {
                \Log::error('Failed to decode image base64 data');
                return null;
            }
            
            // Validate it's a valid image
            $imageInfo = @getimagesizefromstring($imageData);
            if ($imageInfo === false) {
                \Log::error('Invalid image data - not a valid image');
                return null;
            }
            
            // Determine file extension based on image type
            $extension = 'jpg';
            if ($imageInfo[2] === IMAGETYPE_PNG) {
                $extension = 'png';
            } elseif ($imageInfo[2] === IMAGETYPE_JPEG) {
                $extension = 'jpg';
            }
            
            // Generate unique filename
            $filename = 'scanned_' . time() . '_' . uniqid() . '.' . $extension;
            
            // Handle directory path - if it's '/', use empty string, otherwise ensure proper format
            $directory = $this->directory;
            if ($directory === '/') {
                $directory = '';
            } else {
                $directory = rtrim($directory, '/') . '/';
            }
            
            $filePath = $directory . $filename;

            // Save image to storage
            Storage::disk($this->disk)->put($filePath, $imageData);
            
            // Verify file was saved
            if (Storage::disk($this->disk)->exists($filePath)) {
                \Log::info('Image saved successfully: ' . $filePath);
                return $filePath;
            } else {
                \Log::error('Image file was not saved: ' . $filePath);
                return null;
            }
        } catch (\Exception $e) {
            \Log::error('Error saving image: ' . $e->getMessage());
            return null;
        }
    }

    public function getViewData(): array
    {
        $record = $this->getRecord();
        $existingDocumentsRaw = $record ? ($record->valid_id ?? []) : [];
        
        // Normalize existing documents to object format
        $existingDocuments = [];
        foreach ($existingDocumentsRaw as $doc) {
            if (is_array($doc) && isset($doc['path'])) {
                // Already in new format
                $existingDocuments[] = $doc;
            } elseif (is_string($doc)) {
                // Legacy format - convert to object
                $existingDocuments[] = [
                    'name' => basename($doc),
                    'path' => $doc
                ];
            }
        }

        return [
            'disk' => $this->getDisk(),
            'directory' => $this->getDirectory(),
            'maxSize' => $this->getMaxSize(),
            'existingDocuments' => $existingDocuments,
            'statePath' => $this->getStatePath(),
            'state' => $this->getState() ?? [],
        ];
    }
}
