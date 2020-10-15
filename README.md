# ERROR FORMAT

Error Format is used to format error on laravel/lumen framework. It restructures the error object to a simpler-to-use error object

## Installation

Use composer to install Error Format.

```bash
composer require johnvict/error-format
```

## Usage

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Johnvict\ErrorFormat\Services\ErrorFormat;

class ExampleController extends Controller
{
	/**
	 * Add the ErrorFormat trait to your controller to enable the use of validate request as part of the controller class
	 */
	use ErrorFormat;

    public function home(Request $request) {
		// You can define an array of your validation rule
		$errorRule = [
			"name" => "required|string",
			"phone" => "required|string",
			"username" => "required|string",
			"email" => "email"
		];
		// call the validateRequest as follows
		$error = self::validateRequest($request, $errorRule);
		return response()->json($error);
	}
}
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
