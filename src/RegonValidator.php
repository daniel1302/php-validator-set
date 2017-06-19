<?php
namespace Core\Validator;


class RegonValidator extends AbstractValidator
{
    const DEFAULT_MESSAGE = 'Pole %s powinno zawierać poprawny numer regon';

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

        if (!self::checkREGON($this->validateValue)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }

    static public function checkREGON($regon)
    {
        if (strlen($regon) === 9) {
            return self::checkREGON9($regon);
        }

        if (preg_match('/^[0-9]{9}00000$/', $regon)) {
            $regon = substr($regon, 0, 9);
            return self::checkREGON9($regon);
        }

        return self::checkREGON14($regon);
    }

    private static function checkREGON9($regon)
    {
        if (strlen($regon) !== 9) {
            return false;
        }
        $regon = (string)$regon;

        $checkSum = (8*$regon[0] + 9*$regon[1] + 2*$regon[2] + 3*$regon[3]
                  +  4*$regon[4] + 5*$regon[5] + 6*$regon[6] + 7*$regon[7])
                  % 11;

        return (int)$checkSum === $regon[8];
    }

    private static function checkREGON14($regon)
    {
        if (strlen($regon) !== 14) {
            return false;
        }

        $regon = (string)$regon;

        $checkSum = (2*$regon[0] + 4*$regon[1] + 8*$regon[2] + 5*$regon[3] + 0*$regon[4] + 9*$regon[5] + 7*$regon[6]
                  +  3*$regon[7] + 6*$regon[8] + 1*$regon[9] + 2*$regon[10] + 4*$regon[11] + 8*$regon[12])
                  % 11;

        return (int)$checkSum === (int)$regon[13];
    }
}