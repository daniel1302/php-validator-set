<?php
namespace Core\Validator;


class PasswordValidator extends AbstractValidator
{
    const DEFAULT_UPPER_CHAR_FLAG   = true;
    const DEFAULT_SPECIAL_CHARS     = false;
    const DEFAULT_MIN_LENGTH        = 6;
    const UPPER_CHAR_MESSAGE        = 'Hasło musi posiadać przynajmniej jedną dużą literę';
    const SPECIAL_CHARS_MESSAGE     = 'Hasło musi posiadać przynajmniej jeden znak specjalny';
    const MIN_LENGTH_MESSAGE        = 'Hasło musi posiadać przynajmniej {length} znaków';

    private $options = [
        'upper_characters'      => self::DEFAULT_UPPER_CHAR_FLAG,
        'min_length'            => self::DEFAULT_MIN_LENGTH,
        'special_chars'         => self::DEFAULT_SPECIAL_CHARS,
        'upper_char_message'    => self::UPPER_CHAR_MESSAGE,
        'special_chars_message' => self::SPECIAL_CHARS_MESSAGE,
        'min_length_message'    => self::MIN_LENGTH_MESSAGE
    ];

    /**
     * @var
     */
    private $validateValue;

    /**
     * @var string
     */
    private $fieldName;

    public function __construct($value, $fieldName, array $options = [])
    {
        $this->options = array_merge($this->options, $options);
        $this->validateValue = $value;
        $this->fieldName = $fieldName;

        if (!is_bool($this->options['upper_characters'])) {
            $this->options['upper_characters'] = self::DEFAULT_UPPER_CHAR_FLAG;
        }
        if (!is_bool($this->options['special_chars'])) {
            $this->options['special_chars'] = self::DEFAULT_SPECIAL_CHARS;
        }
        if (!is_int($this->options['min_length'])) {
            $this->options['min_length'] = self::DEFAULT_MIN_LENGTH;
        }

        if (!is_string($this->options['upper_char_message']) || empty($this->options['upper_char_message'])) {
            $this->options['upper_char_message'] = self::UPPER_CHAR_MESSAGE;
        }
        if (!is_string($this->options['min_length_message']) || empty($this->options['min_length_message'])) {
            $this->options['min_length_message'] = self::MIN_LENGTH_MESSAGE;
        }
        if (!is_string($this->options['special_chars_message']) || empty($this->options['special_chars_message'])) {
            $this->options['special_chars_message'] = self::SPECIAL_CHARS_MESSAGE;
        }
    }

    /**
     * Make a validation process
     *
     * @return void
     */
    public function validate()
    {
        $this->errors = [];

        if (true === $this->options['upper_characters'] && !preg_match('/[A-Z]+/', $this->validateValue)) {
            $this->errors[] = sprintf($this->options['upper_char_message'], $this->fieldName);
        }
        if (true === $this->options['special_chars'] && !preg_match('/[^a-z0-9]+/i', $this->validateValue)) {
            $this->errors[] = sprintf($this->options['special_chars_message'], $this->fieldName);
        }
        if (strlen($this->validateValue) < (int)$this->options['min_length']) {
            $msg = str_replace('{length}', $this->options['min_length'], $this->options['min_length_message']);
            $this->errors[] = sprintf($msg, $this->fieldName);
        }
    }
}