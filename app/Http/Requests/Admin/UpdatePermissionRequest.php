<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $permId = $this->route('permission') ?? $this->route('id');
        return [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permId,
        ];
    }
}
?>
