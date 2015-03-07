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
            ->add('name')
            ->add('hash')
            ->add('description', 'textarea', array(
                'attr' => array(
                    'style' => 'width: 800px',
                    'rows' => 5
                )
            ))
            ->add('image')
            ->add('image_rectangular')
            ->add('note', 'textarea',array(
                'attr' => array(
                    'style' => 'width: 800px',
                    'rows' => 5
                )
            ))
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
