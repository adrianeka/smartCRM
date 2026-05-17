<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static ?string $pollingInterval = null;
    protected string $view = 'filament.widgets.welcome-widget';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public function getRoleName(): string
    {
        $path = request()->path();
        
        if (str_contains($path, 'manager-dashboard')) return 'Manager';
        if (str_contains($path, 'marketing-dashboard')) return 'Marketing';
        if (str_contains($path, 'sales-dashboard')) return 'Sales';
        if (str_contains($path, 'support-dashboard')) return 'Support';
        
        return 'Admin';
    }
}
