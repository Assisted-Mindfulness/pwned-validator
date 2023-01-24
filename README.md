# Pwned Passwords Validator for Laravel

[![Tests](https://github.com/Assisted-Mindfulness/pwned-validator/actions/workflows/phpunit.yaml/badge.svg)](https://github.com/Assisted-Mindfulness/pwned-validator/actions/workflows/phpunit.yaml)

Introducing the Pwned Password validator - a simple and effective tool for ensuring the security of your users' passwords. This package checks the user's submitted password (during registration or password change) against the [HIBP Pwned Passwords](https://haveibeenpwned.com/Passwords)  database, which contains over half a billion real-world passwords that have been exposed in data breaches.

If a password is found to be pwned, it will fail validation and the user will be prevented from using it in your app. This helps to protect your users from potential security breaches and ensures that they are using strong, unique passwords. With the Pwned Password validator, you can have peace of mind knowing that your users' data is secure. Try it out today and see the difference it makes in the security of your app.



## Installation

Install the package using Composer:

```bash
composer require assisted-mindfulness/pwned-validator/pwned-validator
```

Add the validation message to your validation lang file  `lang/en/validation.php` :

```php
'pwned' => 'The :attribute is not secure enough',
```

or use `:min` in the message to indicate the minimum number of times found set on the validator:

```php
'pwned' => 'Your password is insufficiently secure as it has been found at least :min times in known password breaches, please choose a new one.',
```

## Using the `pwned` validator

After installation, the `pwned` validator will be available for use directly in your validation rules.

```php
return Validator::make($data, [
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:6|pwned|confirmed',
]);
```

## Using the Rule Object

Alternatively, you can use the `AssistedMindfulness\Pwned\PwnedRule` [Validation Rule Object](https://laravel.com/docs/validation#using-rule-objects)
instead of the `pwned` alias if you prefer:

```php
return Validator::make($data, [
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => ['required', 'string', 'min:6', new \AssistedMindfulness\Pwned\PwnedRule, 'confirmed'],
]);
```

## Limiting by the number of times the password was pwned

You can also limit rejected passwords to those that have been pwned a minimum number of times.
For example, `password` has been pwned 3,303,003 times, however `P@ssword!` has only been pwned 118 times.
If we wanted to block `password` but not `P@ssword!`, we can specify the minimum number as 150 like this:

```php
'password' => 'required|string|min:6|pwned:150|confirmed',
```

or using the Rule object:

```php
'password' => ['required', 'string', 'min:6', new \AssistedMindfulness\Pwned\PwnedRule(150), 'confirmed'],
```


## License

This package is a fork of https://github.com/valorin/pwned-validator

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
