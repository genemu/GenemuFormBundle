# JQueryColor Field ([view demo](http://www.eyecon.ro/colorpicker/))

![Color picker](https://github.com/genemu/GenemuFormBundle/raw/master/Resources/doc/jquery/color/images/default.png)

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('color', 'genemu_jquerycolor')
        ->add('colorpicker', 'genemu_jquerycolor', array(
            'widget' => 'image'
        ))
}
```
