<?php

namespace iamsaint\yml\validators;

use iamsaint\yml\interfaces\ValidatorInterface;
use iamsaint\yml\BaseObject;

/**
 * Class required
 * @package iamsaint\yml\validators
 */
class required implements ValidatorInterface
{
    /**
     * @param BaseObject $BaseObject
     * @param array $attributes
     * @param array $options
     */
    public function validate(BaseObject $BaseObject, array $attributes, array $options = []): void
    {
        foreach ($attributes as $attribute) {
            if (trim((string) $BaseObject->$attribute) === '') {
                $BaseObject->addError($attribute, $attribute.' cannot be empty');
            }
        }
    }
}