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