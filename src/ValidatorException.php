<?php
namespace Core\Validator;


class ValidatorException extends \RuntimeException
{
    const NON_VALID_CHAIN_CODE = 10;
    const NON_EXISTING_FIELD_CODE = 11;

    public static function forNonExistingField($identifier, \Exceotion $prev = null)
    {
        return new self(
            sprintf('Field "%s" you want to validate has not validate', (string)$identifier),
            self::NON_EXISTING_FIELD_CODE,
            $prev
        );
    }

    public static function forNonValidatedChain(\Exception $prev = null)
    {
        return new self('Form must be valid before check if fields is valid.', self::NON_VALID_CHAIN_CODE, $prev);
    }
}

