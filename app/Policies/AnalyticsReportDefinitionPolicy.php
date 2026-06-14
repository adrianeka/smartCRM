<?php

namespace App\Policies;

use App\Models\AnalyticsReportDefinition;
use App\Models\User;

class AnalyticsReportDefinitionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('View:Analytics');
    }

    public function view(User $user, AnalyticsReportDefinition $report): bool
    {
        return $user->hasAnyRole(['super_admin', 'Manager/Analyst'])
            || $report->owner_id === $user->id
            || $report->visibility === 'team';
    }

    public function create(User $user): bool
    {
        return $user->can('View:Analytics');
    }

    public function update(User $user, AnalyticsReportDefinition $report): bool
    {
        return $user->hasAnyRole(['super_admin', 'Manager/Analyst'])
            || $report->owner_id === $user->id;
    }

    public function delete(User $user, AnalyticsReportDefinition $report): bool
    {
        return $this->update($user, $report);
    }
}
