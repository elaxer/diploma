<?php

namespace App\Controller\Admin;

use App\Entity\SocialNetwork;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Contracts\Translation\TranslatorInterface;

class SocialNetworkCrudController extends AbstractCrudController
{
    private TranslatorInterface $t;

    public function __construct(TranslatorInterface $t)
    {
        $this->t = $t;
    }

    public static function getEntityFqcn(): string
    {
        return SocialNetwork::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', $this->t->trans('Социальная сеть')),
            UrlField::new('url', $this->t->trans('Адрес соц. сети')),
            TextField::new('iconClass', $this->t->trans('Класс иконки')),
        ];
    }
}
