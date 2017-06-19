<?php
namespace Core\Validator;


class IBANValidator extends AbstractValidator
{
    const DEFAULT_INVALID_LENGTH_MSG        = 'Numer IBAN konta bankowego powinien posiadać 28 znaków';
    const DEFAULT_INVALID_COUNTRY_CODE_MSG  = 'Niepoprwny kod państwa';
    const DEFAULT_INVALID_CHECKSUM_MSG      = 'Niepoprawna suma kontrolna';

    private $options = [
        'invalid_length_message'        => self::DEFAULT_INVALID_LENGTH_MSG,
        'invalid_country_code_message'  => self::DEFAULT_INVALID_COUNTRY_CODE_MSG,
        'invalid_checksum_message'          => self::DEFAULT_INVALID_CHECKSUM_MSG
    ];

    private $validateValue;

    private $fieldName;

    public function __construct($value, $fieldName, array $options = [])
    {
        $this->validateValue = $value;
        $this->fieldName = $fieldName;

        $this->options = array_merge($this->options, $options);

        if (!is_string($this->options['invalid_length_message']) || empty($this->options['invalid_length_message'])) {
            $this->options['invalid_length_message'] = self::DEFAULT_INVALID_LENGTH_MSG;
        }

        if (!is_string($this->options['invalid_country_code_message']) || empty($this->options['invalid_country_code_message'])) {
            $this->options['invalid_country_code_message'] = self::DEFAULT_INVALID_COUNTRY_CODE_MSG;
        }

        if (!is_string($this->options['invalid_checksum_message']) || empty($this->options['invalid_checksum_message'])) {
            $this->options['invalid_checksum_message'] = self::DEFAULT_INVALID_CHECKSUM_MSG;
        }
    }


    /**
     * Make a validation process
     *
     * @return void
     */
    public function validate()
    {
        if (!self::validLength($this->validateValue)) {
            $this->errors[] = sprintf($this->options['invalid_length_message'], $this->fieldName);
        }

        if (!self::validCountryCode($this->validateValue)) {
            $this->errors[] = sprintf($this->options['invalid_country_code_message'], $this->fieldName);
        }

        if (!self::validCheckSum($this->validateValue)) {
            $this->errors[] = sprintf($this->options['invalid_checksum_message'], $this->fieldName);
        }

    }

    private static $charMap = [
        'a' => 10,
        'b' => 11,
        'c' => 12,
        'd' => 13,
        'e' => 14,
        'f' => 15,
        'g' => 16,
        'h' => 17,
        'i' => 18,
        'j' => 19,
        'k' => 20,
        'l' => 21,
        'm' => 22,
        'n' => 23,
        'o' => 24,
        'p' => 25,
        'q' => 26,
        'r' => 27,
        's' => 28,
        't' => 29,
        'u' => 30,
        'v' => 31,
        'w' => 32,
        'x' => 33,
        'y' => 34,
        'z' => 35,
    ];


    public static function validateIBAN($input)
    {
        return self::validLength($input) &
               self::validCountryCode($input) &
               self::validCheckSum($input);
    }

    private static function validLength($input)
    {
        return strlen($input) === 28;
    }

    private static function validCountryCode($input)
    {
        $inputArray = str_split($input);

        if (!isset(self::$charMap[ $inputArray[0] ]) || !isset(self::$charMap[ $inputArray[1] ])) {
            return false;
        }

        return true;
    }

    private static function validCheckSum($input)
    {
        $input      = strtolower($input);
        $inputArray = str_split($input);
        $length     = strlen($input);
        $controlNumber = 97;


        $newInputArr = [];
        for ($k=4; $k<$length; $k++) {
            $newInputArr[] = (int)$inputArray[$k];
        }

        $newInputArr[] = self::$charMap[$inputArray[0]];
        $newInputArr[] = self::$charMap[$inputArray[1]];
        $newInputArr[] = (int)$inputArray[2];
        $newInputArr[] = (int)$inputArray[3];

        $number = 0;
        foreach ($newInputArr as $value) {
            $number .= $value;

            $number = (int)$number % $controlNumber;
        }

        if ($number !== 1) {
            return false;
        }

        return true;
    }
}