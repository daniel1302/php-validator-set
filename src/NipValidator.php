<?php
namespace Core\Validator;


class NipValidator extends AbstractValidator
{
    const DEFAULT_MSG = 'Nip jest niepoprawny';

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
        if (!self::checkNIP($this->validateValue)) {
            $this->errors[] = sprintf($this->options['msg'], $this->fieldName);
        }
    }

    /**
     * @param $nip
     * @return bool
     */
    public static function checkNIP($nip)
    {
        $nipWithoutDashes = preg_replace("/-/", "", $nip);
        $reg = '/^[0-9]{10}$/';

        if(!preg_match($reg, $nipWithoutDashes))
            return false;
        else
        {
            $digits = str_split($nipWithoutDashes);
            $checksum = (6*intval($digits[0]) + 5*intval($digits[1]) +
                         7*intval($digits[2]) + 2*intval($digits[3]) +
                         3*intval($digits[4]) + 4*intval($digits[5]) +
                         5*intval($digits[6]) + 6*intval($digits[7]) +
                         7*intval($digits[8])) % 11;

            return (intval($digits[9]) === $checksum);
        }
    }
}
