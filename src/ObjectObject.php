<?php

namespace iamsaint\yml;

use iamsaint\yml\exceptions\IncorrectRuleExceptin;
use XMLWriter;
use function {
    is_array,
    is_string,
    count,
    array_key_exists
};

/**
 * Class BaseObject
 * @package iamsaint\yml
 */
class BaseObject
{
    /**
     * @var XMLWriter $writer
     */
    public $writer;

    public $errors = [];

    /**
     * BaseObject constructor.
     * @param XMLWriter|null $writer
     */
    public function __construct(XMLWriter $writer = null)
    {
        $this->writer = $writer;
    }

    /**
     * @param string $groupTag
     * @param array|BaseObject[] $elements
     */
    public function writeElements(string $groupTag, array $elements): void
    {
        $this->writer->startElement($groupTag);
        foreach ($elements as $element) {
            $element->setWriter($this->writer)->write($this->writer);
        }

        $this->writer->endElement();
    }

    /**
     * @param XMLWriter $writer
     * @return $this
     */
    public function setWriter(XMLWriter $writer): self
    {
        $this->writer = $writer;
        return $this;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->errors = [];
        $rules = $this->rules();
        foreach ($rules as $rule) {
            if (!is_array($rule)) {
                throw new IncorrectRuleExceptin('Rule must be array');
            }

            if (count($rule) < 2) {
                throw new IncorrectRuleExceptin('Rule is not defined');
            }

            if (!is_string($rule[1])) {
                throw new IncorrectRuleExceptin('Rule name must be a string');
            }

            $class = '\\iamsaint\\yml\\validators\\'.$rule[1];

            if (!class_exists($class)) {
                throw new IncorrectRuleExceptin('Validator not found');
            }

            $attributes = is_array($rule[0]) ? $rule[0] : [$rule[0]];
            (new $class())->validate($this, $attributes, $rule[2] ?: []);
        }

        return count($this->errors) === 0;
    }

    /**
     * @param string $attribute
     * @param string $text
     */
    public function addError($attribute, $text): void
    {
        if (!array_key_exists($attribute, $this->errors)) {
            $this->errors[$attribute] = [];
        }

        $this->errors[$attribute][] = $text;
    }
}