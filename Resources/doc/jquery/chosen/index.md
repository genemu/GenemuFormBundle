# JQueryAutocomplete Field ([view demo](http://jqueryui.com/demos/autocomplete/))

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('country', 'genemu_jquerychosen', array(
            'widget' => 'country'
        ))
        ->add('timezone', 'genemu_jquerychosen', array(
            'widget' => 'timezone'
        ))
        ->add('language', 'genemu_jquerychosen', array(
            'widget' => 'language'
        ));
}
```

## Extra

1. [Choices](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/choices.md)
2. [Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/entity.md)
3. [MongoDB](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/mongodb.md)
4. [Propel](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/propel.md)
