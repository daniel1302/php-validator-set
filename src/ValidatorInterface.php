<?php
namespace Core\Validator;


interface ValidatorInterface
{
    /**
     * Return array of errors
     * @return array
     */
    public function getErrors();

    /**
     * Tell us if given value is valid
     * @return mixed
     */
    public function isValid();

    /**
     * Make a validation process
     *
     * @return void
     */
    public function validate();
}