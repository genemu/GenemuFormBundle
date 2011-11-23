# ReCaptcha Field ([create acount](http://www.google.com/recaptcha))

## Minimal configuration:

``` yml
# app/config/config.yml
genemu_form:
    recaptcha:
        public_key:  `your public key is required`
        private_key: `your private key is required`
```

## Default Usage:

``` php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('captcha', 'genemu_recaptcha');
}
```
