# Default configuration File Field:

``` yml
# app/config/config.yml
genemu_form:
    file:
        enabled:    true
        cancel_img: '/bundes/genemuform/images/cancel.png'
        folder:     '/upload'
        disable_guess_extension: true
        custom_storage_folder: true
```
disable_guess_extension - disable setting file extension based on mime type (it doesnt work in some cases, for example with .mp4 stream video)

option for jqueryimage (custom_storage_folder: true (default value false))
If you store images in custom folders and your entity return full image URI, you should use option

