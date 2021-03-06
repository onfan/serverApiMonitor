<?php
/**
 * User: Joan Teixidó <joan@laiogurtera.com>
 * Date: 07/03/15
 * Time: 17:47
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UrlType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('keys', 'collection', array(
                'type' => new KeyType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false

            ))
            ->add('save', 'submit', array('label' => 'Create url'))
        ;
    }

    /**
    /**
     * @return string
     */
    public function getName()
    {
        return 'url';
    }
}
