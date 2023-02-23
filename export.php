<?php

namespace Models;

use Controllers\Categories;
use \Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
    protected $fillable = array('name', 'alias', 'childrens');
    public $timestamps = false;


//    public function parent() {
//        return $this->hasMany('\Models\Category');
//    }


    public function importDb()
    {
        $data_import = json_decode(file_get_contents("categories.json"), true);

        foreach ($data_import as $category) {
            $c = new Category;
            $c->name = $category['name'];
            $c->alias = $category['alias'];
            $c->childrens = serialize($category['childrens']);
            $c->save();
        }

    }


// Экспорт данных
    public function exportCategorySecond()
    {
        $categories = Category::all();
        $categories_formatted = self::formatCategoriesSecond($categories);

        $output = '';
        foreach ($categories_formatted as $category) {
            $output .= $category['indent'] . $category['name'] . ' ' . PHP_EOL;
        }
        file_put_contents('categories_formatted_second.txt', $output);
        self::showCategorySecond();
    }


    public static function formatCategoriesSecond($categories, $level = 0)
    {

        foreach ($categories as $category) {
            $formattedCategory = [
                'indent' => str_repeat(' ', $level),
                'name' => $category['name']
            ];

            $formattedCategories[] = $formattedCategory;

            if (isset($category->childrens)) {
                $childrens = unserialize($category->childrens);
                if (1 < count($childrens)) {
                    $children = self::formatCategoriesSecond($childrens, $level + 1);
                    $formattedCategories = array_merge($formattedCategories, $children);

                }
            }
        }

        return $formattedCategories;
    }

    public function exportCategoryFirts()
    {
        $categories = Category::all();
        $categories_formatted = self::formatCategoriesFirst($categories);

        $output = '';
        foreach ($categories_formatted as $category) {
            $output .= $category['indent'] . $category['name'] . ' ' . $category['url'] . PHP_EOL;
        }
        file_put_contents('categories_formatted.txt', $output);
        self::showCategoryFirst();
    }


    public function showCategoryFirst()
    {
        $categories = Category::all();
        $categories_formatted = self::formatCategoriesFirst($categories);

        $output = '';
        foreach ($categories_formatted as $category) {
            $output .= $category['indent'] . $category['name'] . ' ' . $category['url'] . PHP_EOL;
        }
        echo '<pre>' . $output . '</pre>';
    }

    public function showCategorySecond()
    {
        $categories = Category::all();
        $categories_formatted = self::formatCategoriesSecond($categories);

        $output = '';
        foreach ($categories_formatted as $category) {
            $output .= $category['indent'] . $category['name'] . ' ' . PHP_EOL;
        }
        echo '<pre>' . $output . '</pre>';
    }


    public static function formatCategoriesFirst($categories, $level = 0)
    {
        foreach ($categories as $category) {
            $formattedCategory = [
                'indent' => str_repeat(' ', $level),
                'name' => $category['name'],
                'url' => self::getUrl($category),
            ];

            $formattedCategories[] = $formattedCategory;

            if (isset($category->childrens)) {
                $childrens = unserialize($category->childrens);

                    foreach ($childrens as $key => $ch) {
                        $ch['parent'] = $category->id;
                        $childrens[$key] = $ch;
                    }


                $children = self::formatCategoriesFirst($childrens, $level + 1);
                $formattedCategories = array_merge($formattedCategories, $children);





                foreach ($childrens as $child) {
                    if (isset($child['childrens'])) {

                        foreach ($child['childrens'] as $key => $chch) {
                            $chch['parent'] = $child['id'];
                            $child['childrens'][$key] = $chch;
                        }

                        $child = $child['childrens'];
                        $children_level = self::formatCategoriesFirst($child, $level + 2);
                       foreach ($children_level as $chch){
                           if ('/users/list/active'== $chch['url']){
                               array_splice($formattedCategories, 3, 0, $children_level);
                            }
                           if ('/reports/marketing/write-offs'== $chch['url']){
                               array_splice($formattedCategories, 12, 0, $children_level);
                           }
                        }

//                        $formattedCategories = array_merge($formattedCategories, $children_level);
                    }
                }






            }

        }

        return $formattedCategories;
    }

//
    public static function getUrl($category)
    {

        $url = '';
        $categories = Category::all();

        foreach ($categories as $cat) {
            if (isset($cat->childrens)) {
                $childrens = unserialize($cat->childrens);


        if (isset($category['parent'])) {
            $category_parent = Category::find($category['parent']);
            if ($category_parent != null) {
                if ($url == ''){
                    $url = '/' . $category_parent['alias'] . '/' . $category['alias'] . $url;
                }

            } else {
                foreach ($childrens as $child) {
                    if ($child['id'] == $category['parent']) {
                        $url = '/' . $cat['alias'] . '/' . $child['alias'] . $url . '/' . $category['alias'] . $url;
                    }
                }
            }

        } else {
               if ($url==''){
                   $url = '/' . $category['alias'] . $url;
               }

        }
            }
        }

        return $url;
    }


}
