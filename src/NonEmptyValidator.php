<?php
namespace Core\Validator;


class NonEmptyValidator extends AbstractValidator
{
    const DEFAULT_MSG = 'Pole %s nie może być puste';

    /**
     * @var array
     */
    private $options = [
        'msg'   => self::DEFAULT_MSG
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
        $this->validateValue    = $value;
        $this->fieldName        = $fieldName;

        $this->options = array_merge($this->options, $options);

        if (!is_string($this->options['msg']) || empty($this->options['msg'])) {
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
        if (empty($this->validateValue)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }

    }
}