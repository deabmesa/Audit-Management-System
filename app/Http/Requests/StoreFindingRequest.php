<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFindingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'audit_id' => ['required', 'exists:audits,id'],
            'title' => ['required', 'string', 'max:255'],
            'risk_level' => ['required', 'in:Low,Medium,High,Critical'],
            'observation' => ['required', 'string'],
            'impact' => ['required', 'string'],
            'root_cause' => ['required', 'string'],
            'recommendation_text' => ['required', 'string'],
            'status' => ['required', 'in:Open,In Progress,Closed'],
        ];
    }
}
