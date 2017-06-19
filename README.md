Usage
========

```phtml
<script type="text/javascript">
    var __INVALID_FIELDS = <?php json_encode($validator->isValid() ? [] : $validator->getInvalidFields()); ?>;

    jQuery('.has-error').removeClass('has-error');

    for (var f in __INVALID_FIELDS) {
        if (!__INVALID_FIELDS.hasOwnProperty(f)) {
            continue;
        }

        jQuery('.'+__INVALID_FIELDS[f]).addClass('has-error');
    }
</script>

<div class="form">
        <input type="text" name="person" placeholder="Firstname and surname"/><br>
        <input type="text" name="street" placeholder="Street"/><br>
        <input type="text" name="postcode" placeholder="Postcode"/><br>
        <input type="text" name="city" placeholder="City"/><br>
        <input type="text" name="email" placeholder="E-mail"/><br>
        <input type="text" name="phone" placeholder="Phone"/><br>
        <select name="country">
            <option value="pl_PL">Poland</option>
            <option value="en_US">United States</option>
            <option value="en_UK">United Kingdom</option>
        </select>
    </div>
```


```php
<?php

if (isset($_POST['form-submit'] {
    
    
    $validator = new \Core\ValidatorChain();
    $countries = [
        'pl_PL' => 'Poland',
        'en_US' => 'United States',
        'en_UK' => 'United Kingdom'
    ];

    $validator
        ->add(
            new \Core\PersonValidator($_POST['person'], 'Imię i nazwisko', ['msg' => 'Podaj poprawne imię i nazwisko nadawcy']),
            'person-class'
        )
        ->add(
            new \Core\NonEmptyValidator($_POST['street'], 'Ulica nadawcy'),
            'street-class'
        )
        ->add(
            new \Core\PostCodeValidator($_POST['postcode'], 'Kod pocztowy nadawcy'),
            'postcode-class'
        )
        ->add(
            new \Core\NonEmptyValidator($_POST['city'], 'Miasto nadawcy'),
            'city-class'
        )
        ->add(
            new \Core\BoolValidator(isset($countries[$_POST['country']]), 'Kraj nadawcy'),
            'country-class'
        )
        ->add(
            new \Core\EmailValidator($_POST['email'], 'E-mail nadawcy'),
            'email-class'
        )
        ->add(
            new \Core\PhoneValidator($_POST['phone'], 'Numer telefonu nadawcy'),
            'phone-class'
        )
    ;
    
    $validator->validate();
    
    if ($validator->isValid()) {
        //Do something
    } else {
        $errorMessages = $validator->getErrorInline();
        $invalidFields = $validator->getInvalidFields();
    }
}
```

Possible value of variables:
1. For $invalidFields:
```
[
    "person-class",
    "postcode-class",
    "email-class",
    "phone-class",
    "person-class",
    "postcode-class",
    "email-class",
    "phone-class"
]
```
2. For $errorMessages
```
[
    "Podaj poprawne imię i nazwisko nadawcy",
    "Pole Kod pocztowy nadawcy powinno zawierać poprawny kod pocztowy",
    "Pole E-mail nadawcy powinno zawierać poprawny adres email",
    "Pole Numer telefonu nadawcy powinno zawierać numer telefonu",
    "Podaj poprawne imię i nazwisko odbiorcy",
    "Pole Kod pocztowy odbiorcy powinno zawierać poprawny kod pocztowy",
    "Pole E-mail odbiorcy powinno zawierać poprawny adres email",
    "Pole Numer telefonu odbiorcy powinno zawierać numer telefonu"
]


