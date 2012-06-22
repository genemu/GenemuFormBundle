# Use JQueryAutocomplete to suggest values on a text field

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('soccer_player', 'genemu_jqueryautocompleter_text', array(
            'suggestions' => array('Ozil', 'Van Persie'),
        ));
}
```

## Extra

[Ajax Suggestions](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/text_ajax.md)
