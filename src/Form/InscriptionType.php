<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class, ["label" => "Nom", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]])
        ->add('prenom',TextType::class, ["label" => "Prénom", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]])
        ->add('email',EmailType::class, ["label" => "Email", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]])
        ->add('password',RepeatedType::class, [
            "type" => PasswordType::class,
            "first_options"=>["label" => "Mot de passe", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]],
            "second_options"=>["label" => "Confirmer le mot de passe", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]]
            ])
        ->add('roles', ChoiceType::class,[
            "attr"=>["class"=>"form-control", "style" => "margin-top:10px"],
            "choices"=>[
                    "Responsable"=>"ROLE_ADMIN",
                    "Client"=>"ROLE_CLIENT"
            ],
            "multiple"=> true,
            "required"=> true
        ])
        ->add("Ajouter",SubmitType::class,["attr" => ["class" => "mt-3 btn btn-success"]])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
