# Tinymce Field ([download tinymce](http://www.tinymce.com/))

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    tinymce: ~
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('content', 'genemu_tinymce');
}
```

## Extra:

[Tinymce and JQuery](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/tinymce/jquery.md)
[Configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/tinymce/default.md)
