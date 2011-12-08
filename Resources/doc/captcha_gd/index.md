# Captcha GD Field

![Default captcha](https://github.com/genemu/GenemuFormBundle/raw/2.0/Resources/doc/captcha_gd/images/default.png)

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
        
        // If you are using form for adding/editing entity (for example with FOSUserBundle user registration form)
        // you may need to mark field as "not a property" by using code

        // ->add('captcha', 'genemu_captcha',array("property_path" => false,));
}
```

## Extra:

[Default configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/captcha_gd/default.md)
