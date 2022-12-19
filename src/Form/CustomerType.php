<?php

namespace App\Form;

use App\Entity\Customer;
use App\Service\CountryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email', EmailType::class, [
                "label" => "Email",
                "attr" => [
                    "maxLength" => 255
                ]
            ])
            ->add('phone', TelType::class, [
                "label" => "n°Phone",
                "attr" => [
                    "maxLength" => 10
                ]
            ])
            ->add('fax', TelType::class, [
                "label" => "n°Fax",
                "attr" => [
                    "maxLength" => 10
                ]
            ])
            ->add('address', TextareaType::class, [
                "label" => "Address",
                "attr" => [
                    "maxLength" => 255
                ]
            ])
            ->add('zipCode', null, [
                "attr" => [
                    "maxLength" => 20
                ]
            ])
            ->add('city', null, [
                "attr" => [
                    "maxLength" => 255
                ]
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
            'data_class' => Customer::class,
        ]);
    }
}
