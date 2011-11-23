# Tinymce Field ([download tinymce](http://www.tinymce.com/))

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    tinymce:
        script_url:  /tinymce/tiny_mce.js `(required)`
```

## Default Usage:

``` php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('content', 'genemu_tinymce');
}
```
