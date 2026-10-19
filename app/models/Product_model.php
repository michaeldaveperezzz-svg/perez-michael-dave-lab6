<?php

class Product_model extends Model
{
    protected $table = 'products';
    protected $primary_key = 'id';

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'quantity'
    ];

    protected $guarded = ['id', 'created_at'];

    public function get_products()
    {
        $stmt = $this->db->raw(
            "SELECT id, product_name, description, price, quantity, created_at
             FROM products
             ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_product($id)
    {
        $stmt = $this->db->raw(
            "SELECT id, product_name, description, price, quantity, created_at
             FROM products
             WHERE id = ?",
            [$id]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create_product($data)
    {
        return $this->db->raw(
            "INSERT INTO products
             (product_name, description, price, quantity)
             VALUES (?, ?, ?, ?)",
            [
                $data['product_name'],
                $data['description'] ?? '',
                $data['price'],
                $data['quantity']
            ]
        );
    }

    public function update_product($id, $data)
    {
        return $this->db->raw(
            "UPDATE products
             SET product_name = ?, description = ?, price = ?, quantity = ?
             WHERE id = ?",
            [
                $data['product_name'],
                $data['description'] ?? '',
                $data['price'],
                $data['quantity'],
                $id
            ]
        );
    }

    public function delete_product($id)
    {
        return $this->db->raw(
            "DELETE FROM products WHERE id = ?",
            [$id]
        );
    }
}