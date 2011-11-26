# Download GenemuFormBundle form Symfony version origin/master

Add the following entries to the deps in the root of your project file:

```
[GenemuFormBundle]
    git=git://github.com/genemu/GenemuFormBundle.git
    target=bundles/Genemu/Bundle/FormBundle
    version=origin/master
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```
