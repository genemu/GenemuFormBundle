# Captcha GD Field

![Default captcha](https://github.com/genemu/GenemuFormBundle/raw/2.0/Resources/doc/captcha_gd/images/default.png)

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    captcha: ~
```

## Fix Bug to IE6 and IE7, add in your routing.yml

``` yml
# app/config/routing.yml
genemu_base64:
    resource: "@GenemuFormBundle/Controller/Base64Controller.php"
    type:     annotation
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

[Default configuration](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/captcha_gd/default.md)
