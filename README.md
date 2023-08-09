# PHP validator

PHP validator allows you to have centralized the validations in one place. Whether you need to validate one, two or more forms.

It can be configured to your liking so as not to depend on a single validation method. You can also view which data was validated correctly and which was not, and even test validation with a specific value(input).

It is based on a class, where all the properties and methods are located. It has a basic system that consists in storage the input values on the side and the ways to validate them on the other side.

## Basic setup

PHP validator acepts three parameters.

* `$Values` : Inputs that will be validated. it Is recomended to pass the `$_POST` variable.
* `$Validations` : Here it is specify the validation type and its validator. If this isn’t especified, then it take the default value (if the input name matches any item of the default list).
* `$Options`:  At the moment there are few options, but they can be important.

For using PHP validator you must download the `validator.php` file. Then to import the file with these code lines.

```php
<?php
    use Validator\ValidatorForm;

	require './validator.php';
	// If the file is in a folder, then you must type require './folder/validator.php'
```

Here is an example.

```php
<?php
    use Validator\ValidatorForm;

    if(!empty($_POST)){
        $validation = new ValidatorForm(
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