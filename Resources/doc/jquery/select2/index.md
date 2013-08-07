# jQuery Select2 Field ([view demo](http://ivaynberg.github.com/select2/))

## Enable it in your app configuration :
``` yml
# app/config/config.yml
genemu_form:
    select2: ~
```


## Download the plugin and include its assets : http://ivaynberg.github.com/select2/

## Default Usage:

You can use all the core choice types from Symfony (choice, country, ...) and
Doctrine (ORM and ODM), you just have to prefix the type name with genemu_jqueryselect2_* :

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('choice', 'genemu_jqueryselect2_choice', array(
            'choices' => array(
                'foo' => 'Foo',
                'bar' => 'Bar',
            )
        ))
        ->add('country', 'genemu_jqueryselect2_country')
        ->add('entity', 'genemu_jqueryselect2_entity', array(
            'class' => 'MyBundle\Entity\Class',
            'property' => 'name',
        ))
    ;
}
```

For more information on options available for each type, cf. to this form type
documentation.

## Extra

1. [Ajax](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/select2/ajax.md)
2. [Configuration example](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/select2/config.md)