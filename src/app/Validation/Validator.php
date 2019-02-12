<?php
/*
|--------------------------------------------------------------------------
| Validator
|--------------------------------------------------------------------------
|
| Validate $request's data
|
*/

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Http\Request;
use SlimFacades\App;


class Validator
{
    protected $errors;

    /**
     * For each rule in $rules, it validates a field with the same name in $request
     *
     * @param Request $request Request to validate
     * @param array $rules Rules for the validation
     * @return object $this
     */
    public function massValidate(Request $request, array $rules)
    {
        foreach($rules as $field => $rule) {
            try {
                $rule->setname(ucFirst($field))->assert($request->getParam($field));
            } catch(NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }
        $session = App::getContainer()->session;
        $session->set('errors', $this->errors);
        return $this;
    }

    /**
     * For each field in $request, validates its based on a $rule in rules with the same name
     *
     * @param Request $request Request to validate
     * @param array $rules Rules for the validation
     * @return object $this
     */
    public function validate(Request $request, array $rules)
    {
        foreach($request->getParams() as $f => $v)
            if(isset($rules[$f])) {
                $rule = $rules[$f];
                try {
                    $rule->setname(ucFirst($f))->assert($v);
                } catch(NestedValidationException $e) {
                    $this->errors[$f] = $e->getMessages();
                }
            }
        $session = App::getContainer()->session;
        $session->set('errors', $this->errors);
        return $this;
    }

    /**
     * Return if the validation has failed or not
     *
     * @return bool true if the validation has failed else false
     */
    public function failed ()
    {
        return !empty($this->errors);
    }

    /**
     * Return all the errors if the validation has failed
     *
     * @return array return the errors
     */
    public function getErrors ()
    {
        return $this->errors;
    }
}
