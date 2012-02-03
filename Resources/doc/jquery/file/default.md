# Default configuration Tinymce Field:

``` yml
# app/config/config.yml
genemu_form:
    file:
        enabled:    true
        cancel_img: '/bundes/genemuform/images/cancel.png'
        folder:     '/upload'
	disable_guess_extension: true
```
disable_guess_extension - disable setting file extension based on mime type (it doesnt work in some cases, for example with .mp4 stream video)