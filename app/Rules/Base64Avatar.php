<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
class Base64Avatar implements Rule
{
    public function passes($attribute, $value)
    {

        if (!in_array($this->mime($value), $this->mimes())) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Avatar rasm bo\'lishi kerak va formati jpeg, png, jpg bo\'lishi kerak';
    }

    public function mime($avatar)
    {
        $type = explode(';', $avatar);
        return explode('/', $type[0])[1];
    }

    public function mimes(): array
    {
        return ['jpeg', 'png', 'jpg'];
    }
}
