<?php namespace MicheleAngioni\Support;

class CustomValidators extends \Illuminate\Validation\Validator
{

    /**
     * alpha_complete permits the following UNICODE characters: alphabetic, numbers, spaces, slashes, pipes ed some punctuation characters
     * N.B. In order to add the '?' the \\ must be used
     * N.B. The final u is a modifier, see http://php.net/manual/en/reference.pcre.pattern.modifiers.php
     *
     * @param  $attribute
     * @param  $value
     *
     * @return int
     */
    public function validateAlphaComplete($attribute, $value)
    {
        return preg_match('/^([|=#-\p{L}*0-9_+!.,:\/;\\?&\(\)\[\]\{\}\'\"\s])+$/u', $value);
    }

    /**
     * alpha_space permits the following UNICODE characters: letters, numbers and spaces
     *
     * @param  $attribute
     * @param  $value
     *
     * @return int
     */
    public function validateAlphaSpace($attribute, $value)
    {
        return preg_match('/^([\p{L}0-9\s])+$/u', $value);
    }

    /**
     * alpha_underscore permits the following UNICODE characters: letters, numbers and underscores
     *
     * @param  $attribute
     * @param  $value
     *
     * @return int
     */
    public function validateAlphaUnderscore($attribute, $value)
    {
        return preg_match('/^([\p{L}0-9_])+$/u', $value);
    }

    /**
     * alphanumeric_names permits the following UNICODE characters: letters, numbers, menus, apostrophes, underscores and spaces
     *
     * @param  $attribute
     * @param  $value
     *
     * @return int
     */
    public function validateAlphanumericNames($attribute, $value)
    {
        return preg_match('/^([-\p{L}0-9\'_\s])+$/u', $value);
    }

    /**
     * alphanumeric_dotted_names permits the following UNICODE characters: letters, numbers, menus, apostrophes, underscores, dots and spaces
     *
     * @param  $attribute
     * @param  $value
     *
     * @return int
     */
    public function validateAlphanumericDottedNames($attribute, $value)
    {
        return preg_match('/^([-\p{L}0-9.\'_\s])+$/u', $value);
    }

    /**
     * alpha_names permits the following UNICODE characters: letters, menus, apostrophes, underscores and spaces
     *
     * @param  $attribute
     * @param  $value
     *
     * @return int
     */
    public function validateAlphaNames($attribute, $value)
    {
        return preg_match('/^([-\p{L}\'_\s])+$/u', $value);
    }

}
