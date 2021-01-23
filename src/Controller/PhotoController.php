<?php declare(strict_types=1);

namespace BlogCore\Controller;

use BlogCore\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Laminas\Diactoros\Response;
use BlogCore\Handler\FileHandler;

class PhotoController extends BaseController
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        // Get All Photos
    }

    /**
     * Display the form for adding the image and text
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $loader = new \Twig\Loader\FilesystemLoader(BASEPATH.'/views');
        $twig = new \Twig\Environment($loader);
        $content = $twig->render('photos/create.html', ['title' => 'Upload Image']);
        $response = new Response\HtmlResponse($content);
        return $response;
    }

    /**
     * Store the file in storage, and upload info to database
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $requiredInput = ['title', 'description'];

        $input = $this->getInput($request, $requiredInput); // finalized input array

        $file = $_FILES['file'];

        $fileData = FileHandler::getFileData($file);

        if($fileData['error'] !== 0){
            $fileErrorMessages = FileHandler::fileErrorMessages();
            $errorData = $fileErrorMessages[$fileData['error']];

            return $this->sendErrorResponse('File upload error', $errorData, 500);
        } // other errors in file upload; return error

        if(FileHandler::verifyExtension($fileData)){
            
            if(FileHandler::verifySize($fileData)){
                $input['file'] = $fileData;

                return new Response\JsonResponse($input);
            }

            return $this->sendErrorResponse('Exceeded the file limit of 1.5MB', [], 422);
        }

        return $this->sendErrorResponse('File extension not supported', [
            'data' => 'Only jpg, jpeg, png and gif are allowed'
        ], 422);
    }

    // auxiliary functions

    /**
     * Get Input Values from Request
     * @param ServerRequestInterface $request
     * @param array $requestList
     * 
     * @return array $input
     */
    public function getInput($request, $requiredInput): array
    {
        $input = [];

        // if empty return error... else filter input
        foreach($requiredInput as $value){

            if(trim($request->getParsedBody()["$value"]) == ""){
                $errorData = "The $value must be provided";
                return $this->sendErrorResponse('Form field error', $errorData, 422);
            }

            $input["$value"] = htmlspecialchars($request->getParsedBody()["$value"]);
        }

        return $input;
    }
}