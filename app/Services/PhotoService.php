<?php

namespace App\Services;

use App\Models\Photo;
use App\Services\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class PhotoService
{
    protected Setting $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Upload and store a photo for a given owner model.
     *
     * @param UploadedFile $file
     * @param Model $owner
     * @return Photo
     */
    public function uploadPhoto(UploadedFile $file, Model $owner, ?string $key = null): Photo
    {
        // Determine key (allow callers to override) and then disk/path from settings
        $key = $key ?? $this->detectKeyFromOwner($owner);
        $disk = $this->setting->diskFor($key);
        $dir = $this->setting->pathFor($key);

        // Store the file
        $path = $file->store($dir, $disk);

        // Check if owner already has a photo
        $existingPhoto = $owner->photo;

        if ($existingPhoto) {
            // Delete old file
            Storage::disk($disk)->delete($existingPhoto->photo_path);
            // Update existing photo record
            $existingPhoto->update([
                'photo_path' => $path,
                'status' => 'A'
            ]);
            return $existingPhoto;
        } else {
            // Create new photo record
            return $owner->photo()->create([
                'photo_path' => $path,
                'status' => 'A'
            ]);
        }
    }

    /**
     * Delete the photo for a given owner model.
     *
     * @param Model $owner
     * @return bool
     */
    public function deletePhoto(Model $owner): bool
    {
        $photo = $owner->photo;

        if ($photo) {
            // Delete the file
            $key = $this->detectKeyFromOwner($owner);
            $disk = $this->setting->diskFor($key);
            Storage::disk($disk)->delete($photo->photo_path);
            // Delete the record
            $photo->delete();
            return true;
        }

        return false;
    }

    /**
     * Get the photo URL for a given owner model.
     *
     * @param Model $owner
     * @return string|null
     */
    public function getPhotoUrl(Model $owner): ?string
    {
        $photo = $owner->photo;

        if ($photo && $photo->status === 'A') {
            $key = $this->detectKeyFromOwner($owner);
            $disk = $this->setting->diskFor($key);
            return Storage::disk($disk)->url($photo->photo_path);
        }

        return null;
    }

    /**
     * Check if the owner has a photo.
     *
     * @param Model $owner
     * @return bool
     */
    public function hasPhoto(Model $owner): bool
    {
        return $owner->photo && $owner->photo->status === 'A';
    }

    protected function detectKeyFromOwner(Model $owner): string
    {
        $class = class_basename($owner);

        switch (strtolower($class)) {
            case 'student':
                return 'student';
            case 'teacher':
                return 'teacher';
            default:
                return 'photo';
        }
    }
}