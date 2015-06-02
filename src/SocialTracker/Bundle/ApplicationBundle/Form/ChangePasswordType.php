<?php

namespace SocialTracker\Bundle\ApplicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_password', 'password', array(
                'mapped' => false,
                'constraints' => array(
                    new NotBlank(),
                )
            ))
            ->add('password', 'repeated', array(
                'property_path' => 'plainPassword',
                'type' => 'password',
                'first_options' => array( 'attr' => array('placeholder' => 'Nouveau password')),
                'second_options' => array('attr' => array('placeholder' => 'Confirmer')),
                'constraints' => array(
                    new NotBlank(),
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SocialTracker\Bundle\ApplicationBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'change_password';
    }
}
