<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => 'sntaksolutionsltd@gmail.com',
            'password' => 'Asdf@1234',
            'remember' => true
        ]);
    }
}
