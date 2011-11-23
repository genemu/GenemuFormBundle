# JQueryAutocomplete Field ([view demo](http://jqueryui.com/demos/autocomplete/))

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('country', 'genemu_jqueryautocompleter', array(
            'widget' => 'country'
        ))
        ->add('language', 'genemu_jqueryautocompleter', array(
            'widget' => 'language'
        ));
}
```

## Extra

### Choices
1. [Choices](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/choices.md)
2. [Ajax Choices](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/choices_ajax.md)

### Entity
1. [Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/entity.md)
2. [Ajax Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/entity_ajax.md)

### MongoDB
1. [MongoDB](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/mongodb.md)
2. [Ajax MongoDB](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/mongodb_ajax.md)


