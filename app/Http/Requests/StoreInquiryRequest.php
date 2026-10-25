<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public const TIMELINES = ['asap', '1_month', '3_months', 'planning'];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'job_title' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'min:7', 'max:30', 'regex:/^[0-9+()\-\s]+$/'],
            'service_id' => ['nullable', Rule::exists('services', 'id')->where('is_published', true)],
            'sector_id' => ['nullable', Rule::exists('sectors', 'id')->where('is_published', true)],
            'location' => ['nullable', 'string', 'max:120'],
            'area' => ['nullable', 'string', 'max:40'],
            'timeline' => ['nullable', Rule::in(self::TIMELINES)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            // Honeypot: real visitors never see or fill this field.
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return collect(['name', 'company', 'job_title', 'email', 'phone', 'location', 'area', 'timeline', 'message'])
            ->mapWithKeys(fn (string $field) => [$field => __("site.contact.{$field}")])
            ->merge(['service_id' => __('site.contact.service'), 'sector_id' => __('site.contact.sector')])
            ->all();
    }
}
