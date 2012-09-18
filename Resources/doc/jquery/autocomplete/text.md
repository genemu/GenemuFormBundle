# Use JQueryAutocomplete to suggest values on a text field

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    autocomplete: ~
```

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // Text suggestions
        ->add('soccer_player', 'genemu_jqueryautocomplete_text', array(
            'suggestions' => array(
                'Ozil',
                'Van Persie'
            ),
        ))
        // Suggestions with doctrine orm
        ->add('soccer_player', 'genemu_jqueryautocomplete_entity', array(
            'class' => 'MyBundle\Entity\MyEntity',
            'property' => 'name',
        ))
        // Suggestions with doctrine odm
        ->add('soccer_player', 'genemu_jqueryautocomplete_document', array(
            'class' => 'MyBundle\Document\MyDocument',
            'property' => 'name',
        ));
}
```

## Extra

[Ajax Suggestions](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/text_ajax.md)
