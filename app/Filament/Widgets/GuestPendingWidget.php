<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class GuestPendingWidget extends Widget
{
    protected static string $view = 'filament.widgets.guest-pending-widget';
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('Guest');
    }
}
