<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Positive;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price', MoneyType::class, options:[
                'currency' => 'USD',
                'constraints' => [
                    new Positive([
                        'message' => 'Price must be positive !'
                    ])
                ]
            ])
            ->add('stock')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'group_by' => 'parent.name',
                'query_builder' => function(CategoryRepository $categoryFilter)
                {
                    return $categoryFilter->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'label' => 'Product Photo (JPEG, PNG, WEBP)',
                'constraints' => [
                    new All(
                        new Image([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/webp'
                            ],
                            'mimeTypesMessage' => 'Only JPEG, PNG, and WEBP are allowed !',
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'File is too large (Max 5M) !'   
                        ])
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
