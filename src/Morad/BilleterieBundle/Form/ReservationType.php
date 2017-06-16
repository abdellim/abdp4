<?php

namespace Morad\BilleterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\LabelType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;



class ReservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                 'widget' => 'single_text',
                 'attr' => ['class' => 'js-datepicker'], 

            ])
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateDeNaissance', BirthdayType::class, array(
            'format' => 'dd-MM-yyyy',
            ))
            ->add('Email', EmailType::class)
            ->add('billet', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => true,
                    'Demi-Journée' => false)))
            ->add('quantite', IntegerType::class, array(
                'data' => '0'))
            ->add('tarifReduit', CheckboxType::class, array('required' => false))
            ->add('pays', CountryType::class)
            ->add('Envoyer', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Morad\BilleterieBundle\Entity\Reservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'morad_billeteriebundle_reservation';
    }


}
