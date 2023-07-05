<?php

/**
 * Record type.
 */

namespace App\Form\Type;

use App\Entity\Author;
use App\Entity\Genre;
use App\Entity\Record;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * RecordType class.
 */
class RecordType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'label.title',
                    'required' => true,
                    'attr' => ['max_length' => 64],
                ]
            )
            ->add(
                'genre',
                EntityType::class,
                [
                    'class' => Genre::class,
                    'choice_label' => function ($genre): string {
                        return $genre->getGenreName();
                    },
                    'label' => 'label.genre_name',
                    'placeholder' => 'label.genre_name',
                    'required' => true,
                ]
            )
            ->add(
                'author',
                EntityType::class,
                [
                    'class' => Author::class,
                    'choice_label' => function ($author): string {
                        return $author->getAlias();
                    },
                    'label' => 'label.alias',
                    'placeholder' => 'label.alias',
                    'required' => true,
                ]
            )
            ->add(
                'inStock',
                IntegerType::class,
                [
                    'label' => 'label.stock',
                    'required' => true,
                ]
            );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Record::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'record';
    }
}
