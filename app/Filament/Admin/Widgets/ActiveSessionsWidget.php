<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class ActiveSessionsWidget extends Widget
{
    protected string $view = 'filament.widgets.active-sessions-widget';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return true;
    }
}
