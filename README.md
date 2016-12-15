# pagination-allegri
php pagination package, work with search query

#Install via composer
```
composer require "hilmanrdn/pagination-allegri":"dev-master"
```

#URL Example
index.php?page=1 //normal<br>
index.php?page=1&search=john&col=username //with search <br>
Search value :will be what we're searching for <br>
Col value :will be which column (on our database)

#How to use it
```
use PaginationAllegri\Pagination;
require_once 'vendor/autoload.php';

//put your database config as parameter
$pagination = new Pagination($host, $dbname, $user, $pass, $table);

//example here user data
$users = $pagination->get_data(); //all user data
$pages = $pagination->get_numbers(); //total page numbers

var_dump($users); //retreive all users data

//pagination
<? for ($i=1; $i<=$pages; $i++): ?>
    <? if($pagination->is_showable($i)): ?>
        <a class="<?=$pagination->is_active_page($i); ?>"
           href="?page=<?=$i . '' .$pagination->get_search_param() ?>">
            <?=$i ?>
        </a>
    <? endif; ?>
<? endfor; ?>

//previous page
<a href="?page=<?=$pagination->prev_page() . '' .$pagination->get_search_param()?>"> << </a>

//next page
<a href="?page=<?=$pagination->next_page() . '' .$pagination->get_search_param()?>"> >> </a>
```
