<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                'attr' =>['Class' => 'form-control'],
                'label_attr' => ['Class' => 'form-label mt-2']
            ])
            ->add('texte', TextareaType::class, [
                'attr' =>['Class' => 'form-control'],
                'label_attr' => ['Class' => 'form-label mt-2']
            ])
            ->add('publie',CheckboxType::class,[
                'attr' =>['Class' => 'form-check-input'],
                'label_attr' => ['Class' => 'form-label mt-1'],
                'required' => false
                ])
            ->add('date', DateTimeType::class ,[
                'attr' =>['Class' => 'form-control'],
                'label_attr' => ['Class' => 'form-label mt-1'],
                'widget' => 'single_text'
            ])
            ->add('image', FileType::class ,[
                'attr' =>['Class' => 'form-control'],
                'label_attr' => ['Class' => 'form-label mt-1'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG ou GIF).',
                    ]),
                ],
            ])
            ->add('ajouter', SubmitType::class, ['label' => 'Valider'])
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}