<?php
/**
 * Reservation type.
 */

namespace App\Form\Type;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ReservationType.
 */
class ReservationType extends AbstractType
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
                'email',
                TextType::class,
                [
                    'label' => 'Email:',
                    'required' => true,
                    'attr' => ['max_length' => 64],
                ]
            )
            ->add(
                'nick',
                TextType::class,
                [
                    'label' => 'Nick:',
                    'required' => true,
                    'attr' => ['max_length' => 64],
                ]
            )
            ->add(
                'content',
                TextType::class,
                [
                    'label' => 'Item you want to rent:',
                    'required' => true,
                    'attr' => ['max_length' => 255],
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
        $resolver->setDefaults(['data_class' => Reservation::class]);
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
        return 'reservation';
    }
}