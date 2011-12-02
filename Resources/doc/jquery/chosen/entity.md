# Use JQueryChosen to Entity values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jquerychosen', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\Member',
            'widget' => 'entity'
        ))
        ->add('cities', 'genemu_jquerychosen', array(
            'class' => 'Genemu\Bundle\EntityBundle\Entity\City',
            'widget' => 'entity',
            'multiple' => true
        ));
}
```
