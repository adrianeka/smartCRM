<?php

namespace App\Filament\Widgets;

use App\Services\Dashboard\QuickLinksService;
use Filament\Widgets\Widget;

class QuickLinksWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-links-widget';

    protected int|string|array $columnSpan = 2;

    protected static ?int $sort = 3;

    protected function getViewData(): array
    {
        $user = auth()->user();

        return [
            'links' => app(QuickLinksService::class)->forRole($user?->role),
        ];
    }
}
