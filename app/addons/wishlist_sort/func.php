<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

function fn_wishlist_sort_extract_cart(&$cart, &$user_id, &$type, &$user_type) {

    $old_session_id = '';
    unset($cart['products']);

    // Restore cart content
    if (!empty($user_id)) {
        $item_types = fn_get_cart_content_item_types('X');

        $_prods = db_get_hash_array(
            "SELECT *, ?:product_descriptions.product FROM ?:user_session_products LEFT JOIN"
            . " ?:product_descriptions ON ?:user_session_products.product_id ="
            . " ?:product_descriptions.product_id AND ?:product_descriptions.lang_code = ?s"
            . " WHERE ?:user_session_products.user_id = ?i AND ?:user_session_products.type = ?s"
            . " AND ?:user_session_products.user_type = ?s AND ?:user_session_products.item_type IN (?a)"
            . " ORDER BY ?:product_descriptions.product", 'item_id', CART_LANGUAGE, $user_id, $type, $user_type, $item_types
        );

        if (!empty($_prods) && is_array($_prods)) {
            $cart['products'] = empty($cart['products']) ? array() : $cart['products'];
            foreach ($_prods as $_item_id => $_prod) {
                $old_session_id = $_prod['session_id'];
                $_prod_extra = unserialize($_prod['extra']);
                unset($_prod['extra']);
                $cart['products'][$_item_id] = empty($cart['products'][$_item_id]) ? fn_array_merge($_prod, $_prod_extra, true) : $cart['products'][$_item_id];
            }
        }
    }

}
