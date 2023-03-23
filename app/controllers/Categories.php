<?php

namespace Controllers;

use Models\Category;

class Categories
{
    public static function importDb()
    {
         Category::importDb();
    }

    public static function exportCategoryFirts()
    {
        Category::exportCategoryFirts();
    }


    public static function exportCategorySecond()
    {
        Category::exportCategorySecond();
    }

}