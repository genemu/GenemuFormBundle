# JQueryAutocomplete Field ([view demo](http://jqueryui.com/demos/autocomplete/))

## Default Usage:

``` php
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

1. [Choices](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/choices.md)
2. [Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/entity.md)

3. [Ajax Choices](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/choices_ajax.md)
4. [Ajax Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/entity_ajax.md)
