<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting
{
    /**
     * Get the filesystem disk for a given key.
     * Falls back to `FILESYSTEM_DISK` or `public`.
     */
    public function diskFor(string $key = 'photo'): string
    {
        return config("images.{$key}.disk", env('FILESYSTEM_DISK', 'public'));
    }

    /**
     * Get the storage path (directory) for a given key.
     */
    public function pathFor(string $key = 'photo'): string
    {
        return config("images.{$key}.path", $this->defaultPath($key));
    }

    protected function defaultPath(string $key): string
    {
        switch ($key) {
            case 'logo':
                return 'logos';
            case 'img':
            case 'image':
                return 'images';
            case 'student':
                return 'students';
            case 'teacher':
                return 'teachers';
            case 'photo':
            default:
                return 'photos';
        }
    }

    /**
     * Return a public URL for an owner's photo, or null if none.
     */
    public function urlForOwner(Model $owner): ?string
    {
        $photo = $owner->photo ?? null;

        if (! $photo || ($photo->status ?? null) !== 'A') {
            return null;
        }

        $key = $this->detectKeyFromOwnerClass($owner);
        $disk = $this->diskFor($key);

        return Storage::disk($disk)->url($photo->photo_path);
    }

    /**
     * Return a public URL for a stored path under a given key.
     */
    public function urlForPath(string $key, string $path): string
    {
        // For static assets in public/, use asset(); for storage, use Storage::url
        if (in_array($key, ['logo', 'image'])) {
            $fullPath = $this->pathFor($key) . '/' . $path;
            return asset($fullPath);
        }

        $disk = $this->diskFor($key);
        return Storage::disk($disk)->url($path);
    }

    /**
     * Return the logo URL. Assumes logo is stored in the configured logo path.
     * Uses the configured filename if none provided.
     */
    public function logoUrl(?string $filename = null): string
    {
        $filename = $filename ?? $this->logoFilename();
        return $this->urlForPath('logo', $filename);
    }

    /**
     * Get the logo filename (configurable via config/images.php or LOGO_FILENAME env var).
     */
    public function logoFilename(): string
    {
        return config('images.logo.filename', 'logo.png');
    }

    protected function detectKeyFromOwnerClass(Model $owner): string
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
