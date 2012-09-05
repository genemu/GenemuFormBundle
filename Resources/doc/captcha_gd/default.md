# Default configuration Captcha GD Field:

``` yml
# app/config/config.yml
genemu_form:
    captcha:
        enabled:          true
        width:            100
        height:           40
        length:           4
        format:           'png'
        chars:            '0123456789'
        font_size:        18
        font_color:       ['252525', '8B8787', '550707', '3526E6', '88531E']
        font_dir:         %kernel.root_dir%/../web/bundles/genemuform/fonts
        fonts:            ['akbar.ttf', 'brushcut.ttf', 'molten.ttf', 'planetbe.ttf', 'whoobub.ttf']
        background_color: 'DDDDDD'
        border_color:     '000000'
        grayscale:        false
```
