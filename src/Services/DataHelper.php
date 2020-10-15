<?php

namespace Johnvict\ErrorFormat\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Services\ResponseFormat;

trait DataHelper
{
	/**
	 * ? These static values are calidation rules for all POST requests into our microservice
	 * ? They are used statically from various providers needing them
	 */
	public static $errorArray;
	public static $AirtimeValidationRule = [
		"trace_id" => "required|string",
		"provider_name" => "required|string",
		"receiver" => "required|string",
		"phone" => "required|string",
		"amount" => "required|integer",
		"email" => "email"
	];
	public static $DataValidationRule = [
		"trace_id" => "required|string",
		"package_id" => "required|integer",
		"receiver" => "required|string",
		"phone" => "required|string",
		"email" => "email"
	];
	public static $TvValidationRule = [
		"trace_id" => "required|string",
		"package_id" => "required|integer",
		"receiver" => "required|string",
		"phone" => "required|string",
		"email" => "email"
	];

	public static $PowerValidationRule = [
		"trace_id" => "required|string",
		"provider_id" => "required|integer",
		"receiver" => "required|string",
		"phone" => "required|string",
		"type" => "required|in:prepaid,postpaid",
		"amount" => "required|integer",
		"email" => "email"
	];
	public static $EpinValidationRule = [
		"trace_id" => "required|string",
		"package_id" => "required|integer",
		"phone" => "required|string",
		"unit" => "integer",
		"email" => "email"
	];


	public static $AirtimeVendValidationRule = [
		"payment_reference" => "required|string",
		"transaction_id" => "required|integer",
		"channel" => "required|string",
	];

	public static $DataVendValidationRule = [
		"payment_reference" => "required|string",
		"transaction_id" => "required|integer",
		"channel" => "required|string",
	];
	public static $TvVendValidationRule = [
		"payment_reference" => "required|string",
		"transaction_id" => "required|integer",
		"channel" => "required|string",
	];
	public static $PowerVendValidationRule = [
		"payment_reference" => "required|string",
		"transaction_id" => "required|integer",
		"channel" => "required|string",
	];

	public static $TransactionHistoryValidationRule = [
		"trace_id" => "required|string",
		"phone_number" => "required|string",
		"last_id" => "integer",
		"start_date" => "date|before_or_equal:today",
		"end_date" => "date|before_or_equal:today",
		"receiver" => "string",
		"provider_id" => "integer"

		// ? THESE ARE ONLY USABLE FOR MOBILE MONEY
		// "bank" => "string",
		// "account" => "string"
	];


	/**
	 * ? To ensure a better object whose keys are the parameter keys as expected and values are the error message
	 * @param Mixed $errorArray - Complex array got from Laravel Validator method
	 * @return Mixed or null - An object is returned if there is an unexpected request body or null if no error  
	 */
	public static function formatError($errorArray)
	{
		DataHelper::$errorArray = collect($errorArray);
		$newErrorFormat = DataHelper::$errorArray->map(function ($error) {
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
		if ($validation->fails()) return DataHelper::formatError($validation->errors());
		return false;
	}

	/**
	 * ? To obtain the balance from a file in the the local directory of the microservice
	 * @return Mixed
	 */
	public static function getBalance()
	{
		$file_path = realpath(__DIR__ . '/../../database/account-balance.json');

		// ? If the file never exists, create it with a default value of 0.00
		if ($file_path == false) {
			return self::setBalance([
				"balance" => 0.00,
				"updated_at" => date('D, j M Y, H:i:s T')
			]);
		}

		$accBalance = json_decode(file_get_contents($file_path), false);
		return isset($accBalance->balance) ? $accBalance : json_decode('{ "balance": 0.00, "updated_at": "'. date("D, j M Y, H:i:s T").'"}');
	}

	/**
	 * ? To update the balance on the local, by subtracting the amount from the current balance
	 * "@param number $amount - Amount of the just completed transaction
	 */
	public static function resetBalance($amount)
	{
		$currentBalance = self::getBalance();
		$newBalance = [
			"balance" => $currentBalance->balance - $amount,
			"updated_at" => date('D, j M Y, H:i:s T')
		];
		return self::setBalance($newBalance);
	}

	/**
	 * ? To instantiate an account balance, saved on the local directory of our miroservice
	 * @param Mixed $balance - The balance object which comprises of the BALANCE and the UPDATED_AT fields
	 */
	public static function setBalance($balance)
	{
		return file_put_contents(__DIR__ . '/../../database/account-balance.json', json_encode($balance));
		return $balance;
	}
}
