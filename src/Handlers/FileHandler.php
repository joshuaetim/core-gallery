<?php declare(strict_types=1);

namespace BlogCore\Handlers;


/**
 * This class deals with the handling of uploaded files
 */
class FileHandler
{

    /**
     * Verify File Extension
     * 
     * @param array $file
     */

    public static function verifyExtension($file, $allowedExtensions = []): bool
    {
        $fileInfo = pathinfo($file['name']);

        if(in_array($fileInfo['extension'], $allowedExtensions)){
            return true;
        }
        
        return false;
    }

    /**
     * Server Side handling of file size
     * 
     * @param array $file
     * @param int $size
     */

     public static function verifySize($file, $size = 1572864): bool
     {
         if($file['size'] <= $size){
             return true;
         }

         return false;
     }

     /**
     * Displays message responses for different file upload error codes
     * @return array $data
     */
    public static function fileErrorMessages(): array
    {
        $fileErrorMessages = [
            0 => 'File uploaded successfully',
            1 => 'The server upload_max_size has been exceeded, check php.ini',
            2 => 'The client upload_max_size has been exceeded, check MAX_FILE_SIZE',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'Upload stopped due to a PHP extension.',
        ];

        return $fileErrorMessages;
    }

    /**
     * Upload the file to my local system
     * @param array $file
     */
    public static function uploadFile($file): string
    {
        $adapter = new \League\Flysystem\Local\LocalFilesystemAdapter(BASEPATH.'/web');
        $filesystem = new \League\Flysystem\Filesystem($adapter);

        // handle upload using writeStream
        $stream = fopen($file['tmp_name'], 'r+');
        $prefix = substr(microtime(), -4, 4); // unique number generator
        $name = $prefix . str_replace(' ', '_', $file['name']); // remove space

        try {
            $filesystem->writeStream('/storage/'.$name, $stream);
            fclose($stream);
            return $name;
        } catch (FileSystemError | UnableToWriteFile $th) {
            throw $th;
        }
        return null;

    }
}