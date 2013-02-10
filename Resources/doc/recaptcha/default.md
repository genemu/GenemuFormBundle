# Default configuration ReCaptcha Field:

``` yml
# app/config/config.yml
genenu_form:
    recaptcha:
        enabled:              true
        public_key:           ~ # Required
        private_key:          ~ # Required
        validation:
            host:                 api-verify.recaptcha.net
            port:                 80
            path:                 /verify
            timeout:              10
            code:                 ~
            proxy:                ~
        configs:              [] 
```
