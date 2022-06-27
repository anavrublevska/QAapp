<?php
/**
 * Answer type.
 */

namespace App\Form\Type;

use App\Entity\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AnswerType.
 */
class AnswerType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'content',
            TextType::class,
            [
                    'label' => 'label.description',
                    'required' => true,
                ]
        )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'label.email',
                    'required' => true,
                ]
            )
            ->add(
                'nickname',
                TextType::class,
                [
                    'label' => 'label.nickname',
                    'required' => true,
                ]
            );
//        $builder->add(
//            'question',
//            EntityType::class,
//            [
//                'class' => Question::class,
//                'choice_label' => function ($question): string {
//                    return $question->getTitle();
//                },
//                'label' => 'label.question',
//                'placeholder' => 'label.none',
//                'required' => true,
//            ]
//        );
    }

    /**
     * Configures options.
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Answer::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'answer';
    }
}
