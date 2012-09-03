# Use JQueryChosen to Entity values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jquerychosen_entity', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\Member',
        ))
        ->add('cities', 'genemu_jquerychosen_entity', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\City',
            'multiple' => true
        ));
}
```
