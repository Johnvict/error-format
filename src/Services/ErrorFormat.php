<?php

namespace Johnvict\ErrorFormat\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ErrorFormat
{


	public static $errorArray;
	/**
	 * ? To ensure a better object whose keys are the parameter keys as expected and values are the error message
	 * @param Mixed $errorArray - Complex array got from Laravel Validator method
	 * @return Mixed or null - An object is returned if there is an unexpected request body or null if no error  
	 */
	public static function formatError($errorArray)
	{
		ErrorFormat::$errorArray = collect($errorArray);
		$newErrorFormat = ErrorFormat::$errorArray->map(function ($error) {
			return $error[0];
		});
		return $newErrorFormat;
	}

	/**
	 * ? To validate parameters on incoming requests
	 * ? These validation customizes the validation error
	 * @param Request $requestData - The request body as sent from the client
	 * @return Mixed or null - An object is returned if there is an unexpected request body or null if no error 
	 */
	public static function validateRequest(Request $requestData, array $validationRule)
	{
		$validation = Validator::make($requestData->all(), $validationRule);
		
		// ? Did we get some errors? Okay, restructure the error @here
		if ($validation->fails()) return ErrorFormat::formatError($validation->errors());
		return false;
	}

}
