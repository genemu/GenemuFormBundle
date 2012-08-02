# JQueryImage Field

![Crop image](https://github.com/genemu/GenemuFormBundle/raw/master/Resources/doc/jquery/image/images/crop.png)

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    image: ~
```

## Add in your routing.yml

``` yml
genemu_image:
    resource: "@GenemuFormBundle/Resources/config/routing/image.xml"
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('image', 'genemu_jqueryimage');
}
```

## Extra

[Configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/image/default.md)
