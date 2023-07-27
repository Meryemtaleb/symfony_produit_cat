<?php

namespace App\Form;
use App\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Produits;
use Symfony\Component\Form\Extension\core\Type\TextType;
use Symfony\Component\Form\Extension\core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, ["label" => "Nom du produit", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]])
            ->add('descriptif', TextType::class, ["label" => "Détail du produit", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]])
            ->add('prix', IntegerType::class, ["label" => "Prix du produit", "attr" => ["class" => "form-control", "style" => "margin-top:10px"]])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                "query_builder" => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')->orderBy('c.titre', 'ASC');
                },
                'choice_label' => 'titre',
                "attr" => ["class" => "form-control", "style" => "margin-top:10px;"]            ])
            ->add('fichier', FileType::class, [
                "label" => "Photo du produit",
                "attr" => [
                    "class" => "form-control mb-3",
                    "style" => "margin-top:10px"],
                    "data_class" => null,
                    "required" => false,
                    "constraints" => [
                        new File([
                            "maxSize" => "2000k",
                            "mimeTypes" => [
                                "image/gif",
                                "image/jpeg",
                                "image/png",
                                "image/jpg",
                            ]
                        ])
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
