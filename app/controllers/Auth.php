
<?php

class Auth extends Controller
{
    public function __construct()
    {
    parent::__construct();
    $this->call->database();
    $this->call->library('api');
    }

    public function login()
    {
        $this->api->require_method('POST');

        $input = $this->api->body();

        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->api->respond_error(
                'Username and password are required.',
                422
            );
        }

        $stmt = $this->db->raw(
            'SELECT id, username, password, role
             FROM users
             WHERE username = ?',
            [$username]
        );

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->api->respond_error(
                'Invalid username or password.',
                401
            );
        }

        $tokens = $this->api->issue_tokens([
            'id' => (int) $user['id'],
            'role' => $user['role']
        ]);

        $this->api->respond($tokens);
    }
}
