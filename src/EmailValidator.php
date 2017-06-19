<?php
namespace Core\Validator;


class EmailValidator extends AbstractValidator
{
    const DEFAULT_MSG = 'Pole %s powinno zawierać poprawny adres email';

    /**
     * @var array
     */
    private $options = [
        'msg' => self::DEFAULT_MSG
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
        if (is_string($this->options['msg']) || empty($this->options['msg'])) {
            $this->options['msg'] = self::DEFAULT_MSG;
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

        if (!filter_var($this->validateValue, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }
}