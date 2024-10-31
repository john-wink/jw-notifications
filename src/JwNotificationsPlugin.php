<?php

namespace JohnWink\JwNotifications;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Illuminate\Contracts\Support\Arrayable;
use JohnWink\JwNotifications\Filament\Pages\NotificationsPage;

class JwNotificationsPlugin implements Plugin
{
    public array $notifications = [];

    public array|Arrayable|Closure $channelOptions = [];

    public array|Arrayable|Closure $channelColors = [];

    public string $navigationLabel = 'jw-notifications::general.navigation.label';

    public string $navigationIcon = 'heroicon-o-bell';

    public string $navigationColor = 'info';

    public $model = \JohnWink\JwNotifications\Models\SubscribedChannels::class;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'jw-notifications';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->userMenuItems([
                'notifications' => MenuItem::make()
                    ->label(fn () => __($this->navigationLabel))
                    ->icon($this->navigationIcon)
                    ->color($this->navigationColor)
                    ->url(fn () => NotificationsPage::getUrl(tenant: Filament::getTenant())),
            ])
            ->pages([
                NotificationsPage::class,
            ]);
    }

    public function boot(Panel $panel): void {}

    public function notifications(array $notifications = [])
    {
        $this->notifications = $notifications;

        return $this;
    }

    public function navigationIcon(string $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function navigationLabel(string $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function channels(array|Arrayable|Closure|null $options)
    {
        $this->channelOptions = $options;

        return $this;
    }

    public function channelColors(array|Arrayable|Closure|null $colors)
    {
        $this->channelColors = $colors;

        return $this;
    }

    public function model($model)
    {
        $this->model = $model;

        return $this;
    }
}
