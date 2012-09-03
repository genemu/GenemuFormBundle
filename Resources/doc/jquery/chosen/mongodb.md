# Use JQueryChosen to MongoDB values

## Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        ->add('member', 'genemu_jquerychosen_document', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\Member',
        ))
        ->add('cities', 'genemu_jquerychosen_document', array(
            'class' => 'Genemu\Bundle\DocumentBundle\Document\City',
            'multiple' => true
        ));
}
```
