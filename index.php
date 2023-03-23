<?php
require "config.php";
require "vendor/autoload.php";
use Models\Database;
use Controllers\Categories;
use Controllers\Articles;
$dt = new Database();



if(count($_POST) > 0) {
    $listing = trim($_POST['listing']);
    if($listing == 1) {
        $res = "Импорт данных"; $listing = 0;
        Categories::importDb();
    }
    if($listing == 2) {
        $res = "Экспорт данных"; $listing = 0;
        Categories::exportCategoryFirts();
    }if($listing == 3) {
        $res = "Экспорт данных не далее первого уровня вложенности"; $listing = 0;
        Categories::exportCategorySecond();
    }
} else {$res = "Нет данных"; }


echo '<form method="post">
<button type="submit" name="listing" value="1">Импорт данных</button>
<button type="submit" name="listing" value="2">Экспорт данных первый вариант</button>
<button type="submit" name="listing" value="3">Экспорт данных второй вариант</button>
</form>';

echo $res;