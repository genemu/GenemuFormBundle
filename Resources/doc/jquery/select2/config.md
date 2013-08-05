# jQuery Select2: Set the default configuration

### Default configuration:
``` yml
# app/config/config.yml
genemu_form:
    select2:
        enabled: true
        configs:
            placeholder: Select a value
            width: off
            allowClear: false
            minimumInputLength: 0
```

### Other options:
- maximumInputLength
- separator
- minimumResultsForSearch
- closeOnSelect
- openOnEnter
- dropdownAutoWidth
- selectOnBlur
- loadMorePadding

see officiel select2's documentation for more information:
http://ivaynberg.github.io/select2/
