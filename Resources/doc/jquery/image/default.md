# Default configuration Image Field:

``` yml
# app/config/config.yml
genemu_form:
    image:
        enabled:  true
        selected: 'large'
        filters:  ['rotate', 'bw', 'negative', 'sepia', 'crop']
        thumbnails:
            small:  [100, 100]
            medium: [200, 200]
            large:  [500, 500]
            extra:  [1024, 768]
```
