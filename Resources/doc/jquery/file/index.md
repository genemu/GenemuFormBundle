# JQueryFile Field ([download uploadify](http://www.uploadify.com))

![Multi files](https://github.com/genemu/GenemuFormBundle/tree/master/Resources/doc/jquery/file/images/multiple.png)

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    file:
        uploader: /uploadify/uploadify.swf
```

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('download', 'genemu_jqueryfile')
        ->add('multiple_download', 'genemu_jqueryfile', array(
            'multiple' => true
        ))
        ->add('auto_download', 'genemu_jqueryfile', array(
            'configs' => array(
                'auto' => true
            )
        ))
        ->add('auto_multiple_download', 'genemu_jqueryfile', array(
            'multiple' => true,
            'configs' => array(
                'auto' => true
            )
        ));
}
```

## Extra

[Configuration](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/file/default.md)
[Save Entity File](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/file/entity.md)
