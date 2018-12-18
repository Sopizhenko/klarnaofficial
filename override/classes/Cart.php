<?php
class Cart extends CartCore
{
    public function getDeliveryOption($default_country = null, $dontAutoSelectOptions = false, $use_cache = false)
    {
        return parent::getDeliveryOption($default_country, $dontAutoSelectOptions, $use_cache);
    }
}