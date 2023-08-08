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
        private $values;
        
        //-------- Data for the validation
        private $validationType;


        //--------- Constructor
        function __construct(array $values, array $validationType, array $options)
        {
            //------ Values
            $this->values = $values;
            $this->validationType = $validationType;

            //------ Options
            // if the user provides a default minmium number, then this is stored for the original
            if(isset($options['default']['min'])){ $this->default['min'] = $options['default']['min']; }
            
            // if the user provides a default maxmium number, then this is stored for the original
            if(isset($options['default']['max'])){ $this->default['max'] = $options['default']['max']; }
            
            // The user can specify wich inputs will be avoided. If not specified, then none is avoided.
            if(isset($options['avoid'])){ $this->avoid = $options['avoid']; }
        }

    }