<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

/**
 * Serves uploaded files from the public disk, so photos work even when
 * `php artisan storage:link` is missing (the project lives in a OneDrive folder).
 */
class MediaController extends Controller
{
    public function __invoke(string $path)
    {
        abort_if(str_contains($path, '..') || ! Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }
}
