<?php
namespace Core\Validator;


class PESELValidator extends AbstractValidator
{
    const DEFAULT_MESSAGE = 'Pole %s powinno zawierać poprawny numer pesel';

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

        if (!self::checkPESEL($this->validateValue)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }

    static public function checkPESEL($str)
    {
        if (strlen($str) !== 11) {
            return false;
        }

        $arrSteps = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3);

        $intSum = 0;
        for ($i = 0; $i < 10; $i++) {
            $intSum += $arrSteps[$i] * $str[$i];
        }

        $int = (10 - $intSum % 10) % 10;


        return $int === (int)$str[10];
    }
}
