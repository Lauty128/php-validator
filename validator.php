<?php
    namespace Validator;

    use Exception;

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
                'validate' => "/[a-zA-Z\s]{5,50}$/"
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
            return in_array($this->Values[$name], $options); 
        }
        /*-----------------------------------------------------------------------------------------------------------------------------*/

        //----------------------------------------------------------------------------------
        //----------------------------- PUBLIC FUNCTIONS -----------------------------------
        //----------------------------------------------------------------------------------
        //--------- Validate an input
        public function validate(string $name):bool
        {
            try{
                // If the input is in the $avoid array, then an error is returned
                if(in_array($name, $this->avoid)){  throw new Exception('This input is being avoided. Please, enter a valid value or remove the "'.$name.'" element of the <strong>$avoid</strong> array'); }
                

                // If the validation input is in $Validations array, then it has a custom validation
                $is_custom = isset($this->Validations[$name]);
                // If the validation input isn't in $Validations array but in the $default, then it has a default validation
                $is_default = isset($this->default[$name]);
    

                // If the input validation isn't in $Validations and $default array, then that input doesn't exist 
                if(!$is_custom && !$is_default){  throw new Exception('The input name do not exist. Please, enter a valid value'); }
                
                // Get input value
                $value = $this->Values[$name];

                // Get validation type. If this isn't especified, then the default values is used. 
                $type = ($is_custom) ? $this->Validations[$name]['type'] : $this->default[$name]['type'];
                

                // Depending on the validation type, the input is validated
                if($type == 'Regexp'){ 
                    $validator = ($is_custom) ? $this->Validations[$name]['validate'] : $this->default[$name]['validate'];
                    return $this->viaRegExp($value, $validator); 
                }

                if($type == 'Options'){
                    $options = $this->Validations[$name]['validate'];
                    return $this->viaOptions($name, $options);
                }

                if($type == 'Length'){ return $this->viaLength($name, $value); }
    
                // This error is returned if the type isn't "Regexp","Options" or "Length"
                throw new Exception('The validator type is invalid. The type must be <strong>"Regexp"</strong>, <strong>"Options"</strong> or <strong>"Length"</strong>');
            }
            catch(Exception $error){
                echo "<span><strong>Error:</strong> ",$error->getMessage(),"</span>";
                return false;
            }
        }

        //--------- Get array of the results
        public function get_results():array
        {
            // This array will be returned to the final
            $results = [];

            foreach ($this->Values as $name => $value) {
                if(!in_array($name, $this->avoid)) // If the input isn't avoided
                {
                    if(isset($this->Validations[$name]) || isset($this->default[$name])) // If the input exists in $Validations of $default
                    {
                        // The default value will  be used if it isn't in $Validations
                        // If the input is found in both, then the value of $Validations is used
                        
                        $results[$name] = $this->validate($name);
                        // name with validation result added to the array
                    }
                 }
            }
            return $results;
        }

        // Execute validation. If all the inputs are true, then this function returns true.
        public function execute(){
            $results = $this->get_results();
            
            // If at least one is false, then the result is false
            return !in_array(false, $results);
        }   
    }