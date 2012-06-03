# Use JQueryAutocomplete to Entity values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryautocompleter_entity', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\Member',
        )
        ->add('cities', 'genemu_jqueryautocompleter_entity', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\City',
            'multiple' => true
        ));
}
```

## Extra

[Ajax Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/entity_ajax.md)
