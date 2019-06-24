# JQuerySlider Field ([view demo](http://jqueryui.com/demos/slider/))

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('price', 'genemu_jqueryslider');
}
```

## With all options:
``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('price', 'genemu_jqueryslider', array(
            'min'           => 0,
            'max'           => 100,
            'step'          => 1,
            'orientation'   => 'horizontal',
            'animate'       => false
        ))
}
```
