<?php declare(strict_types=1);

namespace BlogCore\Handlers;

use BlogCore\Handlers\FileHandler;

class PhotoHandler extends FileHandler
{
    /**
     * Creates a thumbnail from an Image using the Imagick class
     * @param string $image
     * @return bool 
     */
    public static function createThumbnail($name): bool
    {
        $image = new \Imagick(BASEPATH.'/web/storage/'.$name);
        $image->thumbnailImage(1000, 0);

        return $image->writeImage(BASEPATH.'/web/storage/thumbnails/'.$name);
    }
}