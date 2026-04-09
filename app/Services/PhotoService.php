<?php

namespace App\Services;

use App\Models\Photo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class PhotoService
{
    /**
     * Upload and store a photo for a given owner model.
     *
     * @param UploadedFile $file
     * @param Model $owner
     * @return Photo
     */
    public function uploadPhoto(UploadedFile $file, Model $owner): Photo
    {
        // Store the file
        $path = $file->store('photos', 'public');

        // Check if owner already has a photo
        $existingPhoto = $owner->photo;

        if ($existingPhoto) {
            // Delete old file
            Storage::disk('public')->delete($existingPhoto->photo_path);
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
            Storage::disk('public')->delete($photo->photo_path);
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
            return Storage::disk('public')->url($photo->photo_path);
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
}