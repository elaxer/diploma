<?php

namespace App\Controller\Admin;

use App\Entity\SubCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubCategoryCrudController extends AbstractCrudController
{
    private TranslatorInterface $t;

    public function __construct(TranslatorInterface $t)
    {
        $this->t = $t;
    }

    public static function getEntityFqcn(): string
    {
        return SubCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural($this->t->trans('Подкатегория'))
            ->setEntityLabelInSingular($this->t->trans('Подкатегория'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', $this->t->trans('Название')),
            AssociationField::new('category', $this->t->trans('Категория')),
            AssociationField::new('products', $this->t->trans('Товары'))->hideOnForm()->hideOnDetail(),
        ];
    }
}
