<?php

namespace App\Controller\Admin;

use App\Entity\OrderProduct;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderProductCrudController extends AbstractCrudController
{
    private TranslatorInterface $t;

    public function __construct(TranslatorInterface $t)
    {
        $this->t = $t;
    }

    public static function getEntityFqcn(): string
    {
        return OrderProduct::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
