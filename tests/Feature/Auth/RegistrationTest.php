<?php

test('registration is disabled for public', function () {
    $this->get('/register')->assertStatus(404);
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertStatus(404);
});
