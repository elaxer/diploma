<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderType extends AbstractType
{
    private TranslatorInterface $t;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(TranslatorInterface $translator, UrlGeneratorInterface $urlGenerator)
    {
        $this->t = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->urlGenerator->generate('create_order'))
            ->add('email', EmailType::class, ['label' => $this->t->trans('Почта')])
            ->add('phoneNumber', null, ['label' => $this->t->trans('Номер телефона')])
            ->add('city', null, ['label' => $this->t->trans('Город проживания')])
            ->add('products', HiddenType::class, [
                'mapped' => false,
                'required' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => $this->t->trans('Создать заказ')])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
