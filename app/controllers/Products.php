
<?php

class Products extends Controller
{
    public function __construct()
    {
    parent::__construct();
    $this->call->database();
    $this->call->library('api');
    $this->call->model('Product_model');
    }

    private function valid_id($id)
    {
        return ctype_digit((string) $id) && (int) $id > 0;
    }

    private function validate_product($data)
    {
        if (
            !isset($data['product_name']) ||
            trim((string) $data['product_name']) === '' ||
            strlen((string) $data['product_name']) > 100 ||
            !isset($data['price']) ||
            !is_numeric($data['price']) ||
            (float) $data['price'] < 0 ||
            !isset($data['quantity']) ||
            filter_var($data['quantity'], FILTER_VALIDATE_INT) === false ||
            (int) $data['quantity'] < 0
        ) {
            $this->api->respond_error(
                'Invalid product_name, price, or quantity.',
                422
            );
        }
    }

    public function index()
    {
        $this->api->require_method('GET');
        $this->api->require_jwt();

        $products = $this->Product_model->get_products();
        $this->api->respond(['data' => $products]);
    }

    public function show($id)
    {
        $this->api->require_method('GET');
        $this->api->require_jwt();

        if (!$this->valid_id($id)) {
            $this->api->respond_error('Invalid product ID.', 400);
        }

        $product = $this->Product_model->get_product((int) $id);

        if (!$product) {
            $this->api->respond_error('Product not found.', 404);
        }

        $this->api->respond(['data' => $product]);
    }

    public function store()
    {
        $this->api->require_method('POST');
        $this->api->require_jwt();

        $data = $this->api->body();
        $this->validate_product($data);

        $this->Product_model->create_product($data);

        $this->api->respond([
            'message' => 'Product created successfully.'
        ], 201);
    }

    public function update($id)
    {
        $this->api->require_method('PUT');
        $this->api->require_jwt();

        if (!$this->valid_id($id)) {
            $this->api->respond_error('Invalid product ID.', 400);
        }

        $data = $this->api->body();
        $this->validate_product($data);

        if (!$this->Product_model->get_product((int) $id)) {
            $this->api->respond_error('Product not found.', 404);
        }

        $this->Product_model->update_product((int) $id, $data);

        $this->api->respond([
            'message' => 'Product updated successfully.'
        ]);
    }

    public function patch($id)
    {
        $this->api->require_method('PATCH');
        $this->api->require_jwt();

        if (!$this->valid_id($id)) {
            $this->api->respond_error('Invalid product ID.', 400);
        }

        $existing = $this->Product_model->get_product((int) $id);

        if (!$existing) {
            $this->api->respond_error('Product not found.', 404);
        }

        $data = array_merge($existing, $this->api->body());
        $this->validate_product($data);

        $this->Product_model->update_product((int) $id, $data);

        $this->api->respond([
            'message' => 'Product partially updated successfully.'
        ]);
    }

    public function delete($id)
    {
        $this->api->require_method('DELETE');
        $this->api->require_jwt();

        if (!$this->valid_id($id)) {
            $this->api->respond_error('Invalid product ID.', 400);
        }

        if (!$this->Product_model->get_product((int) $id)) {
            $this->api->respond_error('Product not found.', 404);
        }

        $this->Product_model->delete_product((int) $id);

        $this->api->respond([
            'message' => 'Product deleted successfully.'
        ]);
    }
}
