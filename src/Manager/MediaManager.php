<?php

namespace App\Manager;

use App\Entity\Media;

class MediaManager {

    /**
     * @param Media
     * @param mixed
     * @return Media
     */
    public function copyFile(Media $media, $file)
    {
        return $media;
    }
}