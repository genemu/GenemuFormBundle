# JQueryFile to Entity Field ([download uploadify](http://www.uploadify.com))

## Default Usage:

``` php
<?php
// ...
public function buildForm(FormBuilder $builder, array $options)
{
    $builder
        // ...
        ->add('handle', 'genemu_jqueryfile');
}

public function getDefaultOptions(array $options)
{
    return array(
        'data_class' => 'Genemu\Bundle\EntityBundle\Entity\File'
    );
}
```

## Add to Controller action:

``` php
<?php

namespace MyNamespace;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyClassController extends Controller
{
    public function fileAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $test = $this->getDoctrine()->getEntityManager();
        $file = $test->getRepository('GenemuEntityBundle:File')->find(1);

        $form = $this->createForm(new FileType(), $file);

        $options = $this->container->getParameter('genemu.form.jqueryfile.options');
        $folder = $options['folder'];

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                if ($handle = $file->getHandle()) {
                    $file->setName($handle->getBasename('.' . $handle->guessExtension()));
                    $file->setSize($handle->getSize());
                    $file->setMimeType($handle->getMimeType());
                    $file->setContent(file_get_contents($handle->getPathname()));
                }

                $em->persist($file);
                $em->flush();
            }
        }
    }
}
```
