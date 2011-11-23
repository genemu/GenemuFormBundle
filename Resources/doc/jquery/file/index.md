# JQueryFile Field ([download uploadify](http://www.uploadify.com))

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    jqueryfile:
        uploader:   /uploadify/uploadify.swf
        cancel_img: /uploadify/cancel.png
        folder:     /uploads
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

[Save Entity File](https://github.com/genemu/GenemuFormBundle/blob/master/Resources/doc/jquery/file/entity.md)
