# Default configuration ReCaptcha Field:

``` yml
# app/config/config.yml
genenu_form:
    recaptcha:
        enabled:              true
        public_key:           ~ # Required
        private_key:          ~ # Required
        validation:
            host:                 www.google.com
            port:                 80
            path:                 /recaptcha/api/verify
            timeout:              10
            code:                 ~
            proxy:                ~
        configs:              [] 
```
