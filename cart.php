<?php
session_start();
//session_destroy();
// Add Product
// Remove Product
// Update Product
// Get All Products
// SCHEMA
// id - name - quantity - price - total_cost
class MaestroCart
{

    private function getProductRowId($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['product_id'] === $id) {
                return $key;
            }
        }
        return null;
    }

    private function getCurrentProductsArray()
    {
        return (isset($_SESSION["MaestroCart"]) ? $_SESSION["MaestroCart"] : []);
    }

    private function prepareProductArray($product_id, $product_name, $product_price, $product_quantity, $product_options = '')
    {
        return [
            'product_id'       => $product_id,
            'product_name'     => $product_name,
            'product_price'    => $product_price,
            'product_quantity' => $product_quantity,
            'product_options'  => $product_options,
            'total_cost'       => $product_quantity * $product_price,
        ];
    }

    private function prepareNewProductArray($current_product_key, $product_id, $product_name, $product_price, $product_quantity, $product_options = '')
    {
        $current_product_values   = $this->getCurrentProductsArray()[$current_product_key];
        $current_product_quantity = $current_product_values['product_quantity'];
        $new_product_quantity     = $current_product_quantity + $product_quantity;
        $product_array            = $this->prepareProductArray($product_id, $product_name, $product_price, $new_product_quantity, $product_options);
        return $product_array;
    }
    private function updateProductArray($current_product_key, $product_id, $product_quantity)
    {
        $product_values                     = $this->getCurrentProductsArray()[$current_product_key];
        $product_values['product_quantity'] = $product_quantity;
        $product_values['total_cost']       = $product_quantity * $product_values['product_price'];
        return $product_values;
    }

    public function addProduct($product_id, $product_name, $product_price, $product_quantity, $product_options = '')
    {
        $product_array =
        $this->prepareProductArray($product_id, $product_name, $product_price, $product_quantity, $product_options);
        $current_products    = $this->getCurrentProductsArray();
        $current_product_key = $this->getProductRowId($product_id, $current_products);
        if ($current_product_key != null || $current_product_key === 0) {
            $current_products[$current_product_key] =
            $this->prepareNewProductArray($current_product_key, $product_id, $product_name, $product_price, $product_quantity, $product_options);
        } else {
            $current_products[] = $product_array;
        }
        $_SESSION["MaestroCart"] = $current_products;
    }

    public function updateProduct($product_id, $product_quantity)
    {
        $current_products    = $this->getCurrentProductsArray();
        $current_product_key = $this->getProductRowId($product_id, $current_products);
        if ($current_product_key != null || $current_product_key === 0) {
            $current_products[$current_product_key] =
            $this->updateProductArray($current_product_key, $product_id, $product_quantity);
            $_SESSION["MaestroCart"] = $current_products;

        } else {
            return "Not Found";
        }
    }

    public function removeProduct($product_id)
    {
        $current_products    = $this->getCurrentProductsArray();
        $current_product_key = $this->getProductRowId($product_id, $current_products);
        if ($current_product_key != null || $current_product_key === 0) {
            unset($current_products[$current_product_key]);
            $_SESSION["MaestroCart"] = $current_products;
        } else {
            return "Not Found";
        }
    }

    public function emptyCart()
    {
        if (isset($_SESSION["MaestroCart"])) {
            session_unset($_SESSION["MaestroCart"]);
        }
    }
    public function getProducts()
    {
        return $this->getCurrentProductsArray();
    }

}

$n = new MaestroCart;
//$n->addProduct(6, 'new', 500, 3, ['size' => 'large']);
//$n->updateProduct(700, 50);
//$n->removeProduct(5);
//$n->emptyCart();
print_r($n->getProducts());
