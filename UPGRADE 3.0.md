UPGRADE to 3.0
==============

`3.*` is a transition step before `4.*`, where the supported form types will be reduced.
Deprecation triggers will be added in unsupported types until they're eventually removed in `4.*`.

Even though it requires the composer `@dev`flag, the `3.*` version focuses on stability.
We didn't introduce a separate branch to make maintenance easier.

### Form types

The bundle now mainly focuses on form types being related to a backend application :

- Captcha (requires session, generation of a code with GD extension)
- ReCaptcha (requires an HTTP API call)
- Autocomplete, Select2 (requires custom transformers, AJAX endpoint)
- Plain

All other types should be considered as removed.

### Symfony version

The bundle supports Symfony 2.8 and Symfony 3 as well.

