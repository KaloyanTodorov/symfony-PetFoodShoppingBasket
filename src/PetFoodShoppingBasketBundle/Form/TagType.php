<?php

namespace PetFoodShoppingBasketBundle\Form;

use PetFoodShoppingBasketBundle\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
        [
            'data_class' => Tag::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'pet_food_shopping_basket_bundle_tag_type';
    }
}
