<?php
namespace Core\Validator;


class IdentityCardValidator extends AbstractValidator
{
    const DEFAULT_MESSAGE = 'Pole %s powinno zawierać poprawny numer dowodu osobistego';

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

        if (!self::checkID($this->validateValue)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }

    static public function checkID($idNumber)
    {
        if (strlen($idNumber) !== 9) {
            return false;
        }

        $idNumber = strtoupper($idNumber);

        $weights = [
            '0'=>0,  '1'=>1,  '2'=>2,  '3'=>3,  '4'=>4,  '5'=>5,  '6'=>6,  '7'=>7,  '8'=>8,  '9'=>9,
            'A'=>10, 'B'=>11, 'C'=>12, 'D'=>13, 'E'=>14, 'F'=>15, 'G'=>16, 'H'=>17, 'I'=>18, 'J'=>19,
            'K'=>20, 'L'=>21, 'M'=>22, 'N'=>23, 'O'=>24, 'P'=>25, 'Q'=>26, 'R'=>27, 'S'=>28, 'T'=>29,
            'U'=>30, 'V'=>31, 'W'=>32, 'X'=>33, 'Y'=>34, 'Z'=>35
        ];
        $checkSum = 0;

        $importances = [7, 3, 1, 0, 7, 3, 1, 7, 3];
        for ($i=0; $i<9; $i++) {
            $char = $idNumber[$i];

            if ($i < 3 && $weights[$char] < 10) {
                return false;
            }

            if ($i > 2 && $weights[$char] > 9) {
                return false;
            }

            $checkSum += (int)$weights[$char] * $importances[$i];
        }
        $checkSum %= 10;

        return $checkSum === (int)$idNumber[3];
    }
}
