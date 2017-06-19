<?php
namespace Core\Validator;


class ValidatorChain
{
    /**
     * @var array
     */
    private $validators = [];

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var bool
     */
    private $validated = false;

    /**
     * Add validator for this chain
     *
     * @param ValidatorInterface $validator
     * @param null $field
     * @return ValidatorChain
     */
    public function add(ValidatorInterface $validator, $field = null)
    {
        if ($field === null) {
            $this->validators[] = [$validator];
            return  $this;
        }

        if (!isset($this->validators[$field])) {
            $this->validators[$field] = [];
        }

        $this->validators[$field][] = $validator;

        return $this;
    }

    /**
     * Fire all validators
     */
    public function validate()
    {
        foreach ($this->validators as $fieldValidators) {
            /** @var ValidatorInterface $validator */
            foreach ($fieldValidators as $validator) {
                $validator->validate();
            }
        }

        $this->validated = true;
    }

    /**
     * Get errors grouped by fields
     *
     * @return array
     */
    public function getErrors()
    {
        $this->errors = [];
        foreach ($this->validators as $id => $fieldValidators) {
            /** @var ValidatorInterface $validator */
            foreach ($fieldValidators as $validator) {
                $this->errors[$id] = $validator->getErrors();
            }
        }

        return $this->errors;
    }

    /**
     * Get all errors in one-dimension array
     * @return array
     */
    public function getErrorInline()
    {
        if (empty($this->errors)) {
            $this->getErrors();
        }

        $errors = [];
        foreach ($this->errors as $errorGroup) {
            foreach ($errorGroup as $error) {
                $errors[] = $error;
            }
        }

        return $errors;
    }

    /**
     * Check if form is valid
     *
     * @return bool
     */
    public function isValid()
    {
        foreach ($this->validators as $validatorGroup) {
            /** @var ValidatorInterface $validator */
            foreach ($validatorGroup as $validator) {
                if (!$validator->isValid()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check if fields is invalid
     *
     * @param $field
     * @return bool
     */
    public function isValidField($field)
    {
        if (!$this->validated) {
            throw ValidatorException::forNonValidatedChain();
        }

        if (!isset($this->validators[$field])) {
            throw ValidatorException::forNonExistingField($field);
        }

        if (empty($this->errors)) {
            $this->getErrors();
        }

        if (!isset($this->validators[$field])) {
            return true;
        }

        if (empty($this->validators[$field])) {
            return true;
        }

        return false;
    }

    /**
     * Get names of invalid fields
     *
     * @return array
     */
    public function getInvalidFields()
    {
        if (empty($this->errors)) {
            $this->getErrors();
        }

        $invalidFields = [];

        foreach ($this->errors as $fieldName => $fieldErrors) {
            if (!empty($fieldErrors)) {
                $invalidFields[] = $fieldName;
            }
        }

        return $invalidFields;
    }

    /**
     * @return array
     */
    public function getOneError()
    {
        $errors = $this->getErrorInline();

        return current($errors);
    }
}