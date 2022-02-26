<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductCrudController extends AbstractCrudController
{
    private TranslatorInterface $t;

    public function __construct(TranslatorInterface $t)
    {
        $this->t = $t;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural($this->t->trans('Товары'))
            ->setEntityLabelInSingular($this->t->trans('Товар'))
            ->setDefaultSort(['createdAt' => 'DESC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('createdAt', $this->t->trans('Дата добавления'))
                ->setFormat('dd.Y.MM, HH:mm')
                ->hideOnForm(),
            TextField::new('name', $this->t->trans('Название')),
            TextEditorField::new('description', $this->t->trans('Характеристики, описание')),
            AssociationField::new('subCategory', $this->t->trans('Подкатегория (категория)'))->setRequired(true),
            AssociationField::new('brand', $this->t->trans('Марка'))->setRequired(true),
            TextField::new('model', $this->t->trans('Модель')),
            ImageField::new('image', $this->t->trans('Изображение'))
                ->setBasePath('/uploads/products')
                ->setUploadDir('/public/uploads/products')
                ->setRequired(false),
            MoneyField::new('cost', $this->t->trans('Стоимость'))->setCurrency('KZT')->setStoredAsCents(false),
            MoneyField::new('discountPrice', $this->t->trans('Стоимость со скидкой'))->setCurrency('KZT')->setStoredAsCents(false),
            BooleanField::new('inStock', $this->t->trans('Товар на складе')),
        ];
    }
}
