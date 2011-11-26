# Use JQueryAutocomplete to Entity values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jqueryautocompleter', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\Member',
            'widget' => 'entity'
        ))
        ->add('cities', 'genemu_jqueryautocompleter', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\City',
            'widget' => 'entity',
            'multiple' => true
        ));
}
```

## Extra

[Ajax Entity](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/autocomplete/entity_ajax.md)
