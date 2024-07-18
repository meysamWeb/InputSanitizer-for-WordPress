# InputSanitizer for WordPress

## Overview

**InputSanitizer** is a robust PHP class designed to enhance the security and integrity of user input in WordPress applications. By providing a comprehensive sanitization solution, it ensures that the data processed by your WordPress site is clean, safe, and free from common vulnerabilities like SQL injection and cross-site scripting (XSS).

## Features

- **Versatile Sanitization**: Supports a variety of input types including text, email, number, URL, checkbox, radio buttons, and more.
- **Custom Sanitizers**: Implements custom sanitization methods for checkboxes and radio buttons.
- **Easy Integration**: Simple to use within any WordPress plugin or theme.

## Installation

1. **Download**: Clone or download the repository.
2. **Include**: Include the `InputSanitizer.php` file in your WordPress plugin or theme.

## Usage

To use the `InputSanitizer` class, call the `sanitize` method with the appropriate parameters. Below is an example of how to integrate it into your code.

### Example

```php
<?php

namespace MeysamWeb;

class InputSanitizer {
	/**
	 * Sanitize a field based on its type.
	 *
	 * @param string $field_name The name of the field to sanitize.
	 * @param string $sanitize_type The type of sanitization ('text', 'email', 'number', 'checkbox', 'radio', etc.).
	 * @param array $allowed_values Optional. An array of allowed values for radio buttons or select inputs.
	 *
	 * @return mixed The sanitized value.
	 */
	public static function sanitize( string $field_name, string $sanitize_type, array $allowed_values = []): mixed {
		if (!isset($_POST[$field_name])) {
			return null;
		}

		$value = $_POST[$field_name];

		return match ( $sanitize_type ) {
			'text' => sanitize_text_field( $value ),
			'email' => sanitize_email( $value ),
			'number' => filter_var( $value, FILTER_SANITIZE_NUMBER_INT ),
			'url' => esc_url_raw( $value ),
			'checkbox' => self::sanitize_checkbox( $value ),
			'textarea' => sanitize_textarea_field( $value ),
			'key' => sanitize_key( $value ),
			'radio' => self::sanitize_radio( $value, $allowed_values ),
			default => sanitize_text_field( $value ),
		};
	}

	/**
	 * Custom sanitizer for checkboxes.
	 *
	 * @param mixed $value The value to sanitize.
	 * @return bool True if the checkbox is checked, false otherwise.
	 */
	private static function sanitize_checkbox( mixed $value ): bool {
		return rest_sanitize_boolean($value);
	}

	/**
	 * Custom sanitizer for radio buttons.
	 *
	 * @param mixed $value The value to sanitize.
	 * @param array $allowed_values The array of allowed values.
	 *
	 * @return string|null The sanitized value if it is in the allowed values, otherwise null.
	 */
	private static function sanitize_radio( mixed $value, array $allowed_values): ?string {
		return in_array($value, $allowed_values) ? sanitize_text_field($value) : null;
	}
}
```

## How to Use

Below is an example of how to use the `InputSanitizer` class in a typical WordPress form handling scenario.

#### Form Handling Example
```php
<?php

use MeysamWeb\InputSanitizer;

// Sanitize user input from a registration form
$user_first_name = InputSanitizer::sanitize('user_first_name', 'text');
$user_last_name  = InputSanitizer::sanitize('user_last_name', 'text');
$user_email      = InputSanitizer::sanitize('user_email', 'email');
$user_age        = InputSanitizer::sanitize('user_age', 'number');
$user_website    = InputSanitizer::sanitize('user_website', 'url');
$user_bio        = InputSanitizer::sanitize('user_bio', 'textarea');
$user_newsletter = InputSanitizer::sanitize('user_newsletter', 'checkbox');
$user_gender     = InputSanitizer::sanitize('user_gender', 'radio', ['male', 'female', 'other']);

```

### Applicable Models

The `InputSanitizer` class can be effectively utilized in various models and scenarios within WordPress, such as:

* User Registration Forms: Sanitize user input during the registration process.
* Profile Update Forms: Ensure data integrity when users update their profile information.
* Contact Forms: Clean user input from contact forms to prevent spam and malicious content.
* Comment Forms: Sanitize comments to avoid SQL injection and XSS attacks.

## Contributing

Contributions are welcome! If you have suggestions for improvements or have found a bug, please open an issue or submit a pull request. Make sure to follow the contributing guidelines.

## License
This project is licensed under the MIT License.

## Contact
For questions or support, please contact [MeysamWeb](https://github.com/meysamweb).