<?php
namespace Core\Validator;


class PersonValidator extends AbstractValidator
{
    const DEFAULT_MESSAGE = 'Pole %s powinno zawierać poprawe imie i nazwisko';

    private $options = [
        'msg'   => self::DEFAULT_MESSAGE
    ];


    /**
     * @var string
     */
    private $validateValue;

    /**
     * @var string
     */
    private $fieldName;

    public function __construct($value, $fieldName, array $options = [])
    {
        $this->validateValue = $value;
        $this->fieldName = $fieldName;

        $this->options = array_merge($this->options, $options);
        if (!is_string($this->options['msg']) || empty($this->options['msg'])) {
            $this->options['msg'] = self::DEFAULT_MESSAGE;
        }
    }

    public function validate()
    {
        $this->errors = [];

        if (!preg_match('/[a-zżźćńąśłęóŻŹĆŃĄŚŁĘÓ]{3,}\040+[a-zżźćńąśłęóŻŹĆŃĄŚŁĘÓ]{3,}/i', $this->validateValue)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }
}