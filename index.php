<?php
//change1
//change2
//change3
include('engine/classes/Config.php');
include('engine/classes/Frontend.php');
include('engine/classes/Backend.php');

$f3=require('lib/base.php');
$f3->set('DEBUG',3);

/* main routes (static) */

$f3->route('GET /', 'Frontend->home');
$f3->route('GET /index.php', 'Frontend->home');
$f3->route('GET /oils', 'Frontend->oils');
$f3->route('GET /o-firme-huindai', 'Frontend->aboutHuindai');
$f3->route('GET /reviews', 'Frontend->reviews');
$f3->route('GET /contacts', 'Frontend->contacts');

/* catalog */

$f3->route('GET /catalog', 'Frontend->catalog');
$f3->route('GET /catalog/0', 'Frontend->catalog');
$f3->route('GET /catalog/@categoryId', 'Frontend->openCategory');
$f3->route('GET /catalog/products/@productId', 'Frontend->productDetails');

// backend

$f3->route('GET /admin', 'Backend->home');
$f3->route('GET /login', 'Backend->login');
$f3->route('GET /logout','Backend->logout');

$f3->route('POST /login', function($f3){
    $auth = false;
    //user auth
    $conf = new Config();
    $db = new DB\SQL($conf->params, $conf->user,$conf->pass);
    $autorization=new DB\SQL\Mapper($db,'users');

    $name = $_POST['name'];
    $password = $_POST['password'];
    $f3->set('autorization',$db->exec("SELECT * FROM `users` WHERE `name`='$name' AND `password`='$password'"));
    $autorization = $f3->get('autorization');

    if (count($autorization) > 0) { $auth = true;}
    if ($auth){
        session_start();
        $_SESSION['user'] = $name;
        //show admin root.
        $f3->reroute('/admin');
    } else {
        echo "Введенные данные неверны";
    }
});


$f3->route('GET /admin/addCategory', 'Backend->newCategory');
$f3->route('GET /admin/editCategory/@id', 'Backend->editCategory');
$f3->route('POST /admin/saveCategory', 'Backend->saveCategory');
$f3->route('GET /admin/removeCategory/@id', 'Backend->removeCategory');

$f3->route('GET /admin/newProduct', 'Backend->newProduct');
$f3->route('GET /admin/editProduct/@id', 'Backend->editProduct');
$f3->route('POST /admin/writeProduct', 'Backend->writeProduct');
$f3->route('GET /admin/removeProduct/@id', 'Backend->removeProduct');

$f3->run();