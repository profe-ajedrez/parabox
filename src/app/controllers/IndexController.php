<?php declare(strict_types=1);
namespace App\app\controladores;


use parabox\controllers\BaseController;

class IndexController extends BaseController
{
    const VERSION = "0.1.0";


    public function index()
    {
        echo 'Por lo visto funciono. Yupi!';
    }
}