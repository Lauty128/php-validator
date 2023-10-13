# PHP validator

PHP validator allows you to have centralized the validations in one place. Whether you need to validate one, two or more forms.

It can be configured to your liking so as not to depend on a single validation method. You can also view which data was validated correctly and which was not, and even test validation with a specific value(input).

It is based on a class, where all the properties and methods are located. It has a basic system that consists in storage the input values on the side and the ways to validate them on the other side.

## Indice
* [Basic setup](#b-setup)
* [Validations](#validations)
    * [type](#type)
    * [validate](#validate)
    * [Other way for adding validations](#other-way-for-adding-validations-alternative-option)
* [Options](#options)

## Basic setup {b-setup}

PHP validator acepts three parameters.

* `$Values` : Inputs that will be validated. it Is recomended to pass the `$_POST` variable.
* `$Validations` : Here it is specify the validation type and its validator. If this isn’t especified, then it take the default value (if the input name matches any item of the default list).
* `$Options`:  At the moment there are few options, but they can be important.

```php
new FormValidator(array $Values, array $Validations, array $Options);
```

For using PHP validator you must download the `validator.php` file. Then to import the file with these code lines.

```php
<?php
    use Validator\FormValidator;

    require './validator.php';
    // If the file is in a folder, then you must type require './folder/validator.php'
```

Here an example.

```php
<?php
    use Validator\FormValidator;

    if(!empty($_POST)){
        $validation = new FormValidator(
            $_POST,
            [
                'name' => [
                    'type' => 'Regexp',
                    'validate' => "/[a-zA-Z\s]{5,50}$/"
                ]
                'subject' => [
                    'type' => 'Length'
                ],
                'message' => [
                    'type' => 'Length',
                    'validate' => [
                        'max'=>2500
                    ]
                ]
            ]
        );

        if($validation->execute()){
            echo 'Successful validation!!';
        }
        else{
            echo 'ERROR: not all inputs were validated'
        }
    }
?>
```

Assuming the HTML code is in the same place as the PHP code and the file name is **index.php**, then:

```html
<form class="ContactSection__form" method="post" action="test.php">
    <input type="text" id="name-input" name="name" placeholder="Nombre completo">
    <input type="text" id="name-input" name="name" placeholder="Nombre completo"> 
    <textarea name="message" id="message-input" rows="10" placeholder="Mensaje"></textarea>
    <input type="submit" value="ENVIAR">
</form>
```

## Validations
This parameter is the most important, because it contains the information about wich inputs will be validated and how they will be validated.

`$Validations` is an array with the following structure:
```php
$Validations = [
    'input-1' =>[
        'type' => 'Length | Regexp | Options',
        'validate' => // Validation way
    ],
    'input-2' =>[
        'type' => 'Length | Regexp | Options',
        'validate' => // Validation way
    ]
]
```

### type
As seen in the example, this element can take 3 options.
* Length
* Regexp
* Options

> If the value of `type` is empty or misspelled, for example 'options' or 'Option' instead of 'Options', then, automatically, the value of `type` will be 'Length' and will take its default properties for validating the input

### validate
The `type` element exists to indicate that structure will have the `validate` element. 

If the type is **Length**, then:
```php
$Validations = [
    'message' =>[
        'type' => 'Length',
        'validate' => [
            'min' => 50, // Minimum quantity of characters 
            'max' => 600 // Maximum quantity of characters
        ]
    ]
]
```
> If validate is empty, then the default values of `max` and `min` are taked.

If the type is **Options**, then:
```php
$Validations = [
    'genre' =>[
        'type' => 'Options',
        'validate' => [
            'male',
            'female'
            // The uppercases are important
        ]
        // Values the input can have
    ]
]
```
> If validate is empty, then an error will occur.

If the type is **Regexp**, then:
```php
$Validations = [
    'phone' =>[
        'type' => 'Regexp',
        'validate' => "/^[\d\s-]{6,20}$/"
        // Regular expression with wich the input will be validated
    ]
]
```
> If validate is empty, then an error will occur.

### Other way for adding validations
This way can generate a long code, so it is only recommended for a simple form, with 5 or 6 inputs as maximum.

You can use the `add_validation()` function. This function recives 2 parameters.
```php
$validator = new FormValidator($_POST);

$validator->add_validation(
    'input name',   // Name of the input
    [
        'type' => 'Length | Regexp | Options',
        'validate' => ...
        // The same format that in the constructor
    ]
);
``` 
> If the validator already exists, then this will take its place

## Options
This parameter is an `Array` wich can recive 3 elements:

> If some of these elements aren't specified, then the default value is used. View more in [Default values](#default-values)

### minLength & maxLength
These allow to specify the minimum and maximum number of characters that an input can have for default.
```php
[
    'minLength' => 0 
    'maxLength' => 200
]
```
> If an input doesn't has a defined validation, then this will has a characters minimum of 0 and a characters maximum of 200

## Default values
There are 5(five) default validation values. These default validation allows avoid the step of add the field and its validation type in the constructor parameters or in the add_validation method.

You can add more default validation values, modify or remove them. The list is the following:
```
=> email
    * type = 'Regexp',
    * validate = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/"
            
=> name
    * type = 'Regexp',
    * validate = "/[a-zA-Z\s]{5,50}$/"
            
=> url
    * type = 'Regexp',
    * validate = "/^(http|https):\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(/?)([a-zA-Z0-9\-\._\?\,\'\/\\\+&%\$#\=~])*/"
            
=> linkedin
    * type = 'Regexp',
    * validate = "^(https?://)?(www\.)?linkedin\.com/in/[\w-]+/?$"
            
* password
    * type = 'Regexp',
    * validate = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,30}$"
```
> email, name, url, linkedin and password represents the name attribute of the input tag. 