# Use JQueryAutocomplete to Choices values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('choices', 'genemu_jqueryautocompleter_choice', array(
            'choices' => array(
                'foo' => 'Foo',
                'bar' => 'Bar'
            ),
            'multiple' => true
        ));
}
```

## Extra

[Ajax Choices](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/choices_ajax.md)
