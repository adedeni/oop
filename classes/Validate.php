<?php
class Validate{
    private $_passed = false,//set to false by default to assume that the validation has not passed
    $_errors = [],//this is an array to store the errors
    $_db = null;

    public function __construct(){
        $this->_db = DB::getInstance();//this is to get the instance of the database
    }

public function check($source, $items = []) {
    foreach($items as $item => $rules) {
        foreach($rules as $rule => $rule_value) {
            //this is to loop through the items and rules and apply the rules to the item and add to the errors array, item is the name of the input field like username, password, confirm_password, etc. and rules is the rules that apply to the item like required, min, max, unique, etc. 
                //echo "{$item} {$rule} must be {$rule_value}<br>";//this is to show the item and rule and the value of the rule like var_dump($rule_value);
            $value = trim($source[$item]);//trim is to remove any whitespace from the beginning and end of the string
            //echo $value . "<br>";//this is to show the value of the item i.e username, password, confirm_password, etc., their values
            $item = escape($item);
            if($rule === 'required' && empty($value)) {//this is to ensure we are checking if the item is required and if it is empty only if not we will ignore the rule
                $this->addError("{$item} is required");
            } else if(!empty($value)) {
                switch($rule) {
                    case 'min':
                        if(strlen($value) < $rule_value) {//if the length of the supplied value is less than the rule value, then add an error
                            $this->addError("{$item} must be at least {$rule_value} characters");//this is the error message that will be added to the errors array
                        }
                        break;

                    case 'max':
                        if(strlen($value) > $rule_value) {
                            $this->addError("{$item} must be no more than {$rule_value} characters");
                        }
                        break;

                    case 'matches':
                        if($value != $source[$rule_value]) {
                            $this->addError("{$rule_value} must match {$item}");
                        }
                        break;

                    case 'unique':
                        $check = $this->_db->get($rule_value, [$item, '=', $value]);//remember we got the instance of the database in the constructor at the top
                        if($check->count()) {//if the count is greater than 0, then add an error, that is count is true
                            $this->addError("{$item} already exists");
                        }
                        break;

                    // case 'alnum':
                    //     if(!ctype_alnum($value)) {
                    //         $this->addError("{$item} must contain only letters and numbers");
                    //     }
                    //     break;//incase you want a alphanumeric input field, like the password input field

                    // case 'email':
                    //     if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    //         $this->addError("{$item} is not a valid email address");
                    //     }
                    //     break;//incase there is email input field
                }
            }
        }
    }

    if(empty($this->_errors)) {
        $this->_passed = true;//this is to set the passed to true if there are no errors, because the passed is false by default
    }
    return $this;
}
    private function addError($error) {
        //this is to add an error to the errors array
        $this->_errors[] = $error;
    }
    public function errors(){
        return $this->_errors;
    }
    public function passed(){
        return $this->_passed;
    }
}