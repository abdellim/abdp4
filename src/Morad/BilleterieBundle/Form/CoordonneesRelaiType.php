<?php

namespace Morad\BilleterieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Morad\BilleterieBundle\Form\CordonneesType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CoordonneesRelaiType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("coordonnees", CollectionType::class, array(
            'entry_type' => CoordonneesType::class,
          //  'type' => new CoordonneesType(),
            'allow_add' => true,
            'allow_delete' => true
            )

        )
         ->add('Envoyer', SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'morad_billeteriebundle_coordonneesrelai';
    }


}
