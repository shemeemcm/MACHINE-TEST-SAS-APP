<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // $this->route('organization') provides the ID because we use ApiResource routing
        $orgId = $this->route('organization');
        $organization = \App\Models\Organization::withTrashed()->findOrFail($orgId);
        
        return $this->user()->can('update', $organization);
    }

    public function rules(): array
    {
        $orgId = $this->route('organization');

        return [
            'name' => 'sometimes|required|string|max:255',
            'domain' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('organizations')->ignore($orgId),
            ],
        ];
    }
}
