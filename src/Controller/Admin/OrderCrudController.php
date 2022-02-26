<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class OrderCrudController extends AbstractCrudController
{
    private TranslatorInterface $t;

    public function __construct(TranslatorInterface $t, Environment $twig)
    {
        $this->t = $t;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            //->overrideTemplate('crud/detail', 'admin/product_crud/detail.html.twig')
            ->setEntityLabelInPlural($this->t->trans('Заказы'))
            ->setEntityLabelInSingular($this->t->trans('Заказ'))
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('createdAt', $this->t->trans('Дата создания'))
                ->hideOnForm()
                ->setFormat('dd.Y.MM, HH:mm'),
            EmailField::new('email', $this->t->trans('Почта')),
            TelephoneField::new('phoneNumber', $this->t->trans('Номер телефона')),
            TextField::new('city', $this->t->trans('Город')),
            AssociationField::new('status', $this->t->trans('Статус заявки')),
            CollectionField::new('orderProducts', $this->t->trans('Заказанные товары')),
            MoneyField::new('totalCost', $this->t->trans('Общая стоимость'))
                ->hideOnForm()
                ->setCurrency('KZT')
                ->setStoredAsCents(false),
        ];
    }
}
