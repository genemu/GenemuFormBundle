# JQueryChosen Field ([view demo](http://harvesthq.github.io/chosen/)) 

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('country', 'genemu_jquerychosen_country')
        ->add('timezone', 'genemu_jquerychosen_timezone')
        ->add('language', 'genemu_jquerychosen_language');
}
```

## Extra

1. [Choices](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/choices.md)
2. [Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/entity.md)
3. [MongoDB](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/mongodb.md)
4. [Propel](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/chosen/propel.md)
