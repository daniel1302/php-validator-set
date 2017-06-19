<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 05.06.17
 * Time: 11:55
 */

namespace Core\Validator;


class LengthValidator extends AbstractValidator
{
    const DEFAULT_MIN_RANGE = 0;
    const DEFAULT_MAX_RANGE = PHP_INT_MAX;
    const DEFAULT_MESSAGE   = 'Wartość pola %s musi mieć długość od {min} do {max}';

    /**
     * @var array
     */
    private $options = [
        'min' => self::DEFAULT_MIN_RANGE,
        'max' => self::DEFAULT_MAX_RANGE,
        'msg' => self::DEFAULT_MESSAGE
    ];

    /**
     * @var int|float
     */
    private $validateValue;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * ValidatorInterface constructor.
     * @param $value
     * @param $fieldName
     * @param $options
     */
    public function __construct($value, $fieldName, array $options = [])
    {
        $this->validateValue = $value;
        $this->fieldName     = $fieldName;
        $this->options       = array_merge($this->options, $options);


        if (!is_numeric($this->options['min'])) {
            $this->options['min'] = self::DEFAULT_MIN_RANGE;
        }

        if (!is_numeric($this->options['max'])) {
            $this->options['max'] = self::DEFAULT_MAX_RANGE;
        }

        if ($this->options['min'] > $this->options['max']) {
            $this->options['min'] = self::DEFAULT_MIN_RANGE;
        }

        if (empty($this->options['msg']) || !is_string($this->options['msg'])) {
            $this->options['msg'] = self::DEFAULT_MESSAGE;
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

        if (!empty($this->validateValue) && is_scalar($this->validateValue)
            && strlen($this->validateValue) >= $this->options['min'] && strlen($this->validateValue) <= $this->options['max']
        ) {
            return;
        }

        $msg = sprintf($this->options['msg'], $this->fieldName);
        $msg = str_replace('{min}', $this->options['min'], $msg);
        $msg = str_replace('{max}', $this->options['max'], $msg);
        $this->errors[] = $msg;
    }
}