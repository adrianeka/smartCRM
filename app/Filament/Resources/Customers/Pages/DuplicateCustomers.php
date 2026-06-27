<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class DuplicateCustomers extends Page
{
    protected static string $resource = CustomerResource::class;

    protected string $view = 'filament.resources.customers.pages.duplicate-customers';

    protected static ?string $title = 'Check Duplicates Customer';

    public function getDuplicateCandidates(): array
    {
        $customers = Customer::with(['assignedUser', 'tags', 'customFields'])->orderBy('id')->get();
        $candidates = [];

        for ($i = 0; $i < $customers->count(); $i++) {
            for ($j = $i + 1; $j < $customers->count(); $j++) {
                $score = $this->duplicateScore($customers[$i], $customers[$j]);

                if ($score >= 50) {
                    $candidates[] = [
                        'score' => $score,
                        'reasons' => $this->duplicateReasons($customers[$i], $customers[$j]),
                        'primary' => $customers[$i],
                        'duplicate' => $customers[$j],
                    ];
                }
            }
        }

        usort($candidates, fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        return array_slice($candidates, 0, 20);
    }

    public function mergePair(int $primaryId, int $duplicateId): void
    {
        DB::transaction(function () use ($primaryId, $duplicateId): void {
            $primary = Customer::with(['customFields', 'tags', 'attachments'])->findOrFail($primaryId);
            $duplicate = Customer::with(['customFields', 'tags', 'attachments'])->findOrFail($duplicateId);

            if ($primary->is($duplicate)) {
                return;
            }

            $mergeFields = [
                'job_title',
                'website',
                'phone',
                'whatsapp',
                'company_name',
                'industry',
                'identity_number',
                'tax_number',
                'gender',
                'birth_date',
                'address',
                'city',
                'province',
                'postal_code',
                'country',
                'status',
                'customer_type',
                'source',
                'lead_score',
                'preferred_contact_method',
                'last_contacted_at',
                'next_follow_up_at',
                'notes',
                'assigned_user_id',
                'is_favorite',
            ];

            $payload = [];

            foreach ($mergeFields as $field) {
                $payload[$field] = filled($primary->{$field}) ? $primary->{$field} : $duplicate->{$field};
            }

            $primary->update($payload);

            $customFields = $primary->customFields
                ->mapWithKeys(fn ($field) => [$field->field_key => $field->field_value])
                ->all();

            foreach ($duplicate->customFields as $field) {
                $customFields[$field->field_key] ??= $field->field_value;
            }

            $primary->customFields()->delete();
            $primary->customFields()->createMany(
                collect($customFields)
                    ->map(fn ($value, $key) => [
                        'field_key' => $key,
                        'field_value' => $value,
                    ])
                    ->values()
                    ->all()
            );

            $primary->forceFill(['custom_fields' => $customFields])->saveQuietly();
            $primary->tags()->syncWithoutDetaching($duplicate->tags->pluck('id')->all());
            $duplicate->attachments()->update(['customer_id' => $primary->id]);

            activity('customer-merge')
                ->performedOn($primary)
                ->causedBy(auth()->user())
                ->withProperties(['merged_customer_id' => $duplicate->id])
                ->log('Customer duplicate merged from duplicate review page');

            $duplicate->delete();
        });

        Notification::make()
            ->title('Customer duplikat Success digabungkan')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToCustomers')
                ->label('Kembali ke Customer')
                ->url(CustomerResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }

    private function duplicateScore(Customer $first, Customer $second): int
    {
        $score = 0;

        if ($this->sameFilled($first->email, $second->email)) {
            $score += 50;
        }

        if ($this->sameFilled($this->normalizePhone($first->phone), $this->normalizePhone($second->phone))) {
            $score += 30;
        }

        if ($this->sameFilled($this->normalizeText($first->full_name), $this->normalizeText($second->full_name))) {
            $score += 20;
        }

        if ($this->sameFilled($this->normalizeText($first->company_name), $this->normalizeText($second->company_name))) {
            $score += 10;
        }

        return min($score, 100);
    }

    private function duplicateReasons(Customer $first, Customer $second): array
    {
        return collect([
            'Email sama' => $this->sameFilled($first->email, $second->email),
            'Nomor telepon sama' => $this->sameFilled($this->normalizePhone($first->phone), $this->normalizePhone($second->phone)),
            'Name sama' => $this->sameFilled($this->normalizeText($first->full_name), $this->normalizeText($second->full_name)),
            'Same company' => $this->sameFilled($this->normalizeText($first->company_name), $this->normalizeText($second->company_name)),
        ])->filter()->keys()->values()->all();
    }

    private function sameFilled(?string $first, ?string $second): bool
    {
        return filled($first) && filled($second) && mb_strtolower($first) === mb_strtolower($second);
    }

    private function normalizePhone(?string $phone): ?string
    {
        return $phone ? preg_replace('/\D+/', '', $phone) : null;
    }

    private function normalizeText(?string $value): ?string
    {
        return $value ? trim(preg_replace('/\s+/', ' ', mb_strtolower($value))) : null;
    }
}
