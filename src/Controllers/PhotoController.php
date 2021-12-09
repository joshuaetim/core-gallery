<?php declare(strict_types=1);

namespace BlogCore\Controllers;

use BlogCore\Models\Photo;
use BlogCore\Handlers\Database;
use \Laminas\Diactoros\Response;
use BlogCore\Handlers\ViewHandler;
use BlogCore\Handlers\PhotoHandler;
use BlogCore\Handlers\RequestHandler;
use Psr\Http\Message\ResponseInterface;
use BlogCore\Controllers\BaseController;
use BlogCore\Handlers\FileHandler;
use BlogCore\Handlers\Validator;
use Psr\Http\Message\ServerRequestInterface;

class PhotoController extends BaseController
{
    /**
     * Display the form for adding the image and text
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        return ViewHandler::twig('photos/create.html', ['title' => 'Upload Image']);
    }

    /**
     * Store the file in storage, and upload info to database
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $validatorResult = Validator::validate(['title', 'description'], $request->getParsedBody());

        if (array_key_exists("error", $validatorResult)) {
            return $this->sendErrorResponse("Form field error", $validatorResult["error"], 422);
        }

        $file = $_FILES['file'];

        if($file['error'] !== 0){
            $fileErrorMessages = PhotoHandler::fileErrorMessages();
            $errorData = $fileErrorMessages[$file['error']];

            return $this->sendErrorResponse('File upload error', $errorData, 500);
        } // other errors in file upload; return error

        $saveResult = $this->checkAndSendFile($file, $validatorResult);
        
        if ($saveResult['success']) {
            return new Response\RedirectResponse('/');
        } else {
            return $this->sendErrorResponse($saveResult["message"], $saveResult["data"], 422);
        }
    }

    /**
     * Displays the view for editing the photo details
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function edit(ServerRequestInterface $request): ResponseInterface
    {
        $id = RequestHandler::getLastParam($request);
        new Database();
        $photo = Photo::find($id);
        $loader = new \Twig\Loader\FilesystemLoader(BASEPATH.'/views');
        $twig = new \Twig\Environment($loader);
        $content = $twig->render('photos/edit.html', ['title' => 'Edit Image', 'photo' => $photo]);
        $response = new Response\HtmlResponse($content);
        return $response;
    }

    /**
     * Update the photo details
     * @param ServerRequestInterface
     */
    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = RequestHandler::getLastParam($request);
        new Database();
        $photo = Photo::find($id);
        
        $input = [];

        $required = ['title', 'description'];
        $requestBody = $request->getParsedBody();

        foreach($required as $value){
            if(trim($requestBody["$value"]) == ""){
                $errorData = "The $value value must not be empty";
                return $this->sendErrorResponse('Form field error', $errorData, 422);
            }
            $input["$value"] = htmlspecialchars($requestBody["$value"]);
        }

        $photo->update($input);

        $photo = $photo->refresh();

        return new Response\RedirectResponse('/');
    }

    public function delete(ServerRequestInterface $request): ResponseInterface
    {
        $id = RequestHandler::getLastParam($request);
        new Database();
        $photo = Photo::find($id);
        $photoLink = $photo->photo;

        $photo->delete();

        if(PhotoHandler::deleteFile($photoLink)) {
            return new Response\RedirectResponse('/');
        }

        return new Response\TextResponse('error in deleting file');
    }

    // auxiliary functions

    /**
     * Check and Send Photo
     */
    public function checkAndSendFile($file, $input): array
    {
        if(PhotoHandler::verifyExtension($file, ['jpg', 'jpeg', 'png', 'gif'])){
            
            if(PhotoHandler::verifySize($file)){
                // all good, upload file
                $input['file'] = $file;
                
                $path = "";
                if($_ENV["APP_ENV"] == "production"){
                    $path = PhotoHandler::uploadFile($file);
                }
                else {
                    $path = PhotoHandler::uploadFileLocal($file);
                }

                if($path){
                    // db store
                    new Database();
                    // $thumb = PhotoHandler::createThumbnail($path);
                    $photo = Photo::create([
                        'title' => $input['title'],
                        'description' => $input['description'],
                        'photo' => $path,
                        'thumbnail' => $path
                    ]);

                    return ['success' => true];
                }

                die("Failed to upload");
            }
            return [
                'success' => false, 
                'message' => 'Exceeded the file limit of 1.5MB',
                'data' => [],
            ];
        }
        else {
            return [
                'success' => false, 
                'message' => 'File extension not supported',
                'data' => 'Only jpg, jpeg, png and gif are allowed',
            ];
        }
    }
}