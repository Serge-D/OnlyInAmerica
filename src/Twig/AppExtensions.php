<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{
    //permet d'utiliser des variables globales dans les templates twig

    private $cart;

    public function __construct(Cart $cart)
    {
        $this->cart=$cart;
    }

    public function getFilters()
    {
        return[
          new TwigFilter('price', [$this, 'formatPrice']),
          new TwigFilter('html', [$this, 'html'], ['is_safe' => ['html']])
        ];
    }

    public function formatPrice($number)
    {
        return number_format($number, '2',','). ' €';
    }

    public function getGlobals(): array
    {
        return [
            'fullCartQuantity' => $this->cart->fullQuantity()
        ];
    }

    // protection des attaques XSS avec le filtre twig |raw
    public function html($html)
    {
        return $html;
    }

    public function getName()
    {
        return 'acme_extension';
    }
}