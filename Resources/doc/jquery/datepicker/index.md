# JQueryDate Field ([view demo](http://jqueryui.com/demos/datepicker/))

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    date: ~
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('createdAt', 'genemu_jquerydate')
        ->add('updatedAt', 'genemu_jquerydate', array(
            'widget' => 'single_text'
        ));
}
```
