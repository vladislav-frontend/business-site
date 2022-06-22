<?php

namespace App\Services;

class Breadcrumbs
{
  public function __construct($route)
  {
    // dd($route);
    
    // $route_controller = explode('/', $route);
    // $breadcrumbs = array();

    // foreach ($urls as $key => $value)
    // {
    //     //Собираем url для каждого пункта цепочки
    //     $prev_urls = array();
    //     for ($i = 0; $i <= $key; $i++)
    //     {
    //         $prev_urls[] = $urls[$i];
    //     }

    //     //собираем url для всех, кроме текущей страницы
    //     if ($key == count($urls) - 1) $crumbs[$key]['url'] = '';
    //     elseif (!empty($prev_urls)) $crumbs[$key]['url'] = count($prev_urls) > 1 ? implode('/', $prev_urls) : '/';

    //     //Прописываем название пункта, исходя из url
    //     switch ($value)
    //     {
    //         case 'portfolio' :
    //             $crumbs[$key]['text'] = 'Портфолио';
    //             break;
    //         case 'pricingbox' :
    //             $crumbs[$key]['text'] = 'Наши цены';
    //             break;
    //         case 'blog' :
    //             $crumbs[$key]['text'] = 'Блог';
    //             break;
    //         case 'contact' :
    //             $crumbs[$key]['text'] = 'Контакты';
    //             break;
    //         default :
    //             $crumbs[$key]['text'] = 'Главная страница';
    //             break;
    //     }
        
    //     if ($key > 0) $crumbs[$key]['text'] = $crumbs[$key]['text'];
    // }
  }
}