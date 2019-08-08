<?php

namespace iamsaint\yml\validators;

use iamsaint\yml\interfaces\ValidatorInterface;
use iamsaint\yml\BaseObject;

/**
 * Class url
 * @package iamsaint\yml\validators
 */
class url implements ValidatorInterface
{
    /**
     * @param BaseObject $BaseObject
     * @param array $attributes
     * @param array $options
     */
    public function validate(BaseObject $BaseObject, array $attributes, array $options = []): void
    {
        foreach ($attributes as $attribute) {
            if (!filter_var($BaseObject->$attribute, FILTER_VALIDATE_URL)) {
                $BaseObject->addError($attribute, $attribute.' must be valid url');
            }
        }
    }
}