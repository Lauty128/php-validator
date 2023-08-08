<?php
    namespace Validator;

    class ValidatorForm{
        //--------- Default values
        private $default = [
            //---- Minimum number acepted in Length validation
            'min' => 0,
            //---- Maximum number acepted in Length validation
            'max' => 200,

            //---- Default validations
            'email' => [
                'type' => 'Regexp',
                'validate' => "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/"
            ],
            'name' => [
                'type' => 'Regexp',
                'validate' => "[a-zA-Z\s]{5,50}$"
            ],
            'url' => [
                'type' => 'Regexp',
                'validate' => "/^(http|https):\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(/?)([a-zA-Z0-9\-\._\?\,\'\/\\\+&%\$#\=~])*/"
            ],
            'linkedin' => [
                'type' => 'Regexp',
                'validate' => "^(https?://)?(www\.)?linkedin\.com/in/[\w-]+/?$"
            ],
            'password' => [
                'type' => 'Regexp',
                'validate' => "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,30}$"
            ]
        ];

        //--------- Inputs
        public $avoid = [];
        private $Values;
        
        //-------- Data for the validation
        private $Validations;


        //--------- Constructor
        function __construct(array $Values, array $Validations, array $options)
        {
            //------ Values
            $this->Values = $Values;
            $this->Validations = $Validations;

            //------ Options
            // if the user provides a default minmium number, then this is stored for the original
            if(isset($options['default']['min'])){ $this->default['min'] = $options['default']['min']; }
            
            // if the user provides a default maxmium number, then this is stored for the original
            if(isset($options['default']['max'])){ $this->default['max'] = $options['default']['max']; }
            
            // The user can specify wich inputs will be avoided. If not specified, then none is avoided.
            if(isset($options['avoid'])){ $this->avoid = $options['avoid']; }

            // This function is responsible of that the validation type of each element of the $Validations[$name]['type'] has as value = 'Regexp', 'Length' or 'Options'
            $this->review_valitations();
        }

        private function review_valitations():void
        {
            $types = ['Regexp','Length','Options'];
            foreach($this->Validations as $key => $value){
                if(!in_array($value['type'], $types)){
                    //  If this value doesn't match any, it takes the value of 'Length' and its default setting
                    $this->Validations[$key]['type'] = 'Length';
                }
            }
        }

        /*-------------------------------------------------- VALIDATIONS FUNCTIONS ----------------------------------------------------*/
        //--------- Validate through length
        private function viaLength(string $name, string $text):bool
        { 
            // If the Validations[$name]['validate']['max'] doesn't exist, it take the default value
            $quantitymax = (isset($this->Validations[$name]['validate']["max"])) ? $this->Validations[$name]['validate']["max"] : $this->default['max'];
            // If the Validations[$name]['validate']['min'] doesn't exist, it take the default value
            $quantitymin = (isset($this->Validations[$name]['validate']["min"])) ? $this->Validations[$name]['validate']["min"] : $this->default['min']; 
            
            return (strlen($text) <= $quantitymax && strlen($text) >= $quantitymin);
        }
        
        //--------- Validate through expressions regular
        private function viaRegExp(string $value, string $validator):bool
        { 
            return (preg_match($validator, $value) == 1) ? true : false ; 
        }
        
        //--------- Validate through options
        private function viaOptions(string $name, array $options):bool
        { 
            return in_array($this->values[$name], $options); 
        }
        /*-----------------------------------------------------------------------------------------------------------------------------*/
    }