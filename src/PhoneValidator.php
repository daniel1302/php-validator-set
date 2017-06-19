<?php
namespace Core\Validator;


class PhoneValidator extends AbstractValidator
{
    const DEFAULT_MESSAGE = 'Pole %s powinno zawierać numer telefonu';

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

        $phoneNumber = preg_replace('/[\(\)\-\040\+]/', '', $this->validateValue);


        if (!preg_match('/[0-9]{2}-?[0-9]{3}/i', $phoneNumber)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }
}