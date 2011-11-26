# Captcha GD Field

![Default captcha](https://github.com/genemu/GenemuFormBundle/tree/master/Resources/doc/captcha_gd/images/default.png)

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    captcha: ~
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('captcha', 'genemu_captcha');
}
```

## Extra:

[Default configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/captcha_gd/default_configuration.md)
