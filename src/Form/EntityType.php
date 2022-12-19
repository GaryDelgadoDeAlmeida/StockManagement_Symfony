<?php

namespace App\Form;

use App\Entity\Entity;
use App\Service\CountryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EntityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                "label" => "Name",
                "attr" => [
                    "maxLength" => 255
                ],
                "required" => true
            ])
            ->add('socialName', null, [
                "label" => "Social name",
                "attr" => [
                    "maxLength" => 255
                ],
                "required" => true
            ])
            ->add('legalStatus', null, [
                "label" => "Legal status",
                "attr" => [
                    "maxLength" => 255
                ],
                "required" => true
            ])
            ->add('siret', null, [
                "label" => "n°SIRET",
                "attr" => [
                    "maxLength" => 14
                ],
                "required" => true
            ])
            ->add('siren', null, [
                "label" => "n°SIREN",
                "attr" => [
                    "maxLength" => 9
                ],
                "required" => true
            ])
            ->add('address', null, [
                "label" => "Address",
                "attr" => [
                    "maxLength" => 255
                ],
                "required" => true
            ])
            ->add('zipCode', null, [
                "label" => "ZIP Code",
                "attr" => [
                    "maxLength" => 10
                ],
                "required" => true
            ])
            ->add('city', null, [
                "label" => "City",
                "attr" => [
                    "maxLength" => 100
                ],
                "required" => true
            ])
            ->add('country', ChoiceType::class, [
                "label" => "Country",
                "choices" => array_map(function($country) {
                    return $country["name"];
                }, CountryInterface::COUNTRIES),
                "choice_label" => function($country, $key) {
                    return $country;
                },
                "multiple" => false,
                "required" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entity::class,
        ]);
    }
}
