# Use JQueryAutocomplete to Entity values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_autocompleter_entity', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\Member',
        )
        ->add('cities', 'genemu_autocompleter_entity', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\City',
            'multiple' => true
        ));
}
```

## Extra

[Ajax Entity](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/autocomplete/entity_ajax.md)
