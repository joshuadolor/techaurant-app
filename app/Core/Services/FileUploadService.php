<?php

namespace App\Core\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function uploadLogo(UploadedFile $file, string $path = ''): string
    {
        // Debug: Log the incoming file
        \Log::info('FileUploadService - Starting upload', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'is_valid' => $file->isValid(),
            'error' => $file->getError()
        ]);
        // Validate the file
        // Check if file is valid
        if (!$file->isValid()) {
            throw new \Exception('File upload failed: ' . $file->getErrorMessage());
        }
        
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
         // Store the file
         $filePath = $file->storeAs($path, $filename, 'public');
      
        \Log::info('FileUploadService - File stored', [
            'file_path' => $filePath,
            'full_path' => Storage::disk('public')->path($filePath),
            'exists' => Storage::disk('public')->exists($filePath),
            'size' => Storage::disk('public')->size($filePath)
        ]);
         // Return the public URL
         $url = Storage::disk('public')->url($filePath);
        
         \Log::info('FileUploadService - Generated URL', [
             'url' => $url
         ]);
        
        // Return the public URL
        return Storage::disk('public')->url($filePath);
    }
    
    private function validateLogo(UploadedFile $file): void
    {
        $file->validate([
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    }
    
    public function deleteLogo(string $url): bool
    {
        $path = str_replace(Storage::disk('public')->url(''), '', $url);
        return Storage::disk('public')->delete($path);
    }
}