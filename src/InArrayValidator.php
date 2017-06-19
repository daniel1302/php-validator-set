<?php
namespace Core\Validator;


class InArrayValidator extends AbstractValidator
{
    const DEFAULT_MESSAGE = 'Pole %s powinno zawierać jedną z wartości: {expected}';

    private $options = [
        'msg'       => self::DEFAULT_MESSAGE,
        'expected'  => []
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

        if (!is_array($this->options['expected'])) {
            throw new \InvalidArgumentException('Expected values for InArrayValidator should be array');
        }
    }

    public function validate()
    {
        $this->errors = [];

        if (!in_array($this->validateValue, $this->options['expected'])) {
            $msg = str_replace('{expected}', implode(', ', $this->options['expected']), $this->options['msg']);
            $this->errors[] = sprintf($msg, $this->fieldName);
        }
    }
}