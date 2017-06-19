<?php
namespace Core\Validator;


abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Return array of errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Tell us if given value is valid
     * @return mixed
     */
    public function isValid()
    {
        return empty($this->errors);
    }
}