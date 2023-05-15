<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceCheck extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('PriceCheck', [$this, 'PriceCheck']),
        ];
    }

    public function priceCheck($price, $price_promo, $promo_from, $promo_to): string
    {
        $paymentDate = date('Y-m-d');
        $paymentDate = date('Y-m-d', strtotime($paymentDate));

        $price_html = '';

        if( ($promo_from != '0000-00-00') && ($promo_to != '0000-00-00') ){

            if (($paymentDate >= $promo_from) && ($paymentDate <= $promo_to)){
                $price_html = '<div class="product-price-variant">
                                    <span class="price current-price">R$ '.number_format($price_promo,2,",",".").'</span>
                                    <span class="price old-price">R$ '.number_format($price,2,",",".").'</span>
                                </div>';
            }else{
                $price_html = '<div class="product-price-variant">
                                    <span class="price current-price">R$ '.number_format($price,2,",",".").'</span>
                                </div>';
            }

        }else if($price_promo != '0.00'){
            $price_html = '<div class="product-price-variant">
                                <span class="price current-price">R$ '.number_format($price_promo,2,",",".").'</span>
                                <span class="price old-price">R$ '.number_format($price,2,",",".").'</span>
                            </div>';
        }else{
            $price_html = '<div class="product-price-variant">
                                <span class="price current-price">R$ '.number_format($price,2,",",".").'</span>
                            </div>';
        }

        return $price_html;

    }
}