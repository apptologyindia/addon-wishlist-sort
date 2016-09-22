<?php

if ($mode == 'view') {
    $products = Tygh::$app['view']->getTemplateVars('products');

    if (!empty($products)) {
        uasort($products, function ($p1, $p2) {
            return strcmp($p1['product'], $p2['product']);
        });
        
        Tygh::$app['view']->assign('products', $products);
    }
}
