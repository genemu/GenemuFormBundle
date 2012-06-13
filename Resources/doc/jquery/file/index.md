# JQueryFile Field ([download uploadify](http://www.uploadify.com))

![Multi files](https://github.com/genemu/GenemuFormBundle/raw/2.0/Resources/doc/jquery/file/images/multiple.png)

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    file:
        uploader: /uploadify/uploadify.swf
```

## Add in your routing.yml

``` yml
genemu_upload:
    resource: "@GenemuFormBundle/Controller/UploadController.php"
    type:     annotation
```

## Add in your view, after calling the jQuery library
``` twig
<script src="{{ asset('bundles/genemuform/js/genemuFormBundle.js') }}"></script>
{{ form_javascript(form) }}
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

## Usage in Form Collection
When the file upload element is called within a collection, only the configuration is setup in the data-prototype.
In that case, you need to manually trigger the uploadify via a call to 

``` javascript
genemuFormBundleFileEnable(uploadFieldId);
```

## Extra

[Configuration](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/file/default.md)
[Save Entity File](https://github.com/genemu/GenemuFormBundle/blob/2.0/Resources/doc/jquery/file/entity.md)
