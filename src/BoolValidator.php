<?php
namespace Core\Validator;


class BoolValidator extends AbstractValidator
{
    const DEFAULT_MESSAGE = 'Pole %s zawiera niepoprawną wartość';
    const DEFAULT_EXPECTED = true;


    private $options = [
        'msg'       => self::DEFAULT_MESSAGE,
        'expected'  => self::DEFAULT_EXPECTED
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

        if (!is_bool($this->options['expected'])) {
            $this->options['expected'] = self::DEFAULT_EXPECTED;
        }
    }

    public function validate()
    {
        $this->errors = [];

        if ($this->validateValue !== $this->options['expected']) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }
}