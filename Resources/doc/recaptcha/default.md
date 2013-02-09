# Default configuration ReCaptcha Field:

``` yml
# app/config/config.yml
genemu_form:
    recaptcha:
        enabled:    true
        server_url: 'http://api.recaptcha.net'
        ssl:
            use:        true
            server_url: 'https://api-secure.recaptcha.net'
            # use a proxy server for outgoing code verification requests
            proxy:
                enabled: false
                host: '' # hostname of the proxyserver
                port: 80
```
