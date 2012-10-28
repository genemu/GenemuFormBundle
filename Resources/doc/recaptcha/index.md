# ReCaptcha Field ([create acount](http://www.google.com/recaptcha))

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    recaptcha:
        public_key:  `your public key is required`
        private_key: `your private key is required`
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('captcha', 'genemu_recaptcha');
}
```

Also make sure you output form JS in your twig template:

```{{ form_javascript(form) }}```

## Extra:

[Configuration](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/recaptcha/default.md)
