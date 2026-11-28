<button
    type="button"
    class="fi-topbar-database-notifications-btn fi-sidebar-footer-row"
>
    <x-filament::icon
        icon="heroicon-o-bell"
        class="fi-sidebar-footer-row-icon"
    />

    <span class="fi-sidebar-footer-row-label">
        {{ __('filament-panels::layout.actions.open_database_notifications.label') }}
    </span>

    @if ($unreadNotificationsCount)
        <span class="fi-sidebar-footer-row-count">
            {{ $unreadNotificationsCount }}
        </span>
    @endif
</button>
