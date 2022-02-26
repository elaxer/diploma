<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $brands = [
            'Samsung',
            'Xiaomi',
            'Apple',
            'Huawei',
            'LG',
            'Sony',
        ];
        foreach ($brands as $brand) {
            $manager->persist((new Brand())->setName($brand));
        }

        $categories = [
            'Сотовые телефоны' => [
                'Смартфоны',
                'Кнопочные телефоны',
            ],
            'Ноутбуки' => [
                'Ноутбуки',
                'Ультрабуки',
                'Сумки для ноутбуков',
            ],
            'Комплектующие' => [
                'Материнские платы',
                'Процессоры',
                'Видеокарты',
                'Оперативная память',
                'Жесткие диски',
                'Твердотельные накопители',
                'Блоки питания',
            ],
            'Программное обеспечение' => [
                'Антивирусы',
                'Операционные системы',
                'Офисные приложения',
            ],
        ];

        foreach ($categories as $categoryName => $subcategories) {
            $category = (new Category())->setName($categoryName);

            foreach ($subcategories as $subcategoryName) {
                $subcategory = (new SubCategory())->setName($subcategoryName);
                $manager->persist($subcategory);

                $category->addSubCategory($subcategory);
            }

            $manager->persist($category);
        }

        $manager->flush();
    }
}
