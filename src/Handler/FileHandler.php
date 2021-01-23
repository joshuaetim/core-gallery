<?php declare(strict_types=1);

namespace BlogCore\Handler;

/**
 * This class deals with the handling of uploaded files
 */
class FileHandler
{
    /**
     * Gets the file information
     * 
     * Typically receives the $_FILES parameter
     * 
     * @param array $file
     * 
     * @return array $fileData
     */
    public static function getFileData($file): array
    {
        $fileData = [];

        foreach($file as $key => $value){
            $fileData["$key"] = $value;
        }

        return $fileData;
    }

    /**
     * Verify File Extension
     * 
     * @param array $file
     */

    public static function verifyExtension($file): bool
    {
        $fileInfo = pathinfo($file['name']);

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

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
            2 => 'The server upload_max_size has been exceeded, check MAX_FILE_SIZE',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'Upload stopped due to a PHP extension.',
        ];

        return $fileErrorMessages;
    }
}