<?php
declare(strict_types=1);

namespace BlogCore\Handlers;

class Validator
{
    /**
     * Handle the validation of items
     * @param array $fields
     * @return array
     */
    public static function validate(array $fields, $requestBody): array {
        $input = [];

        foreach($fields as $value){
            if(trim($requestBody["$value"]) == ""){
                $errorData = "The $value must be provided";
                return ['error' => $errorData];
            }
            $input["$value"] = htmlspecialchars($requestBody["$value"]);
        }
        return $input;
    }
}