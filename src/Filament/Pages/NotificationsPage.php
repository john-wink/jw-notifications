<?php

declare(strict_types=1);

namespace JohnWink\JwNotifications\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JohnWink\JwNotifications\Models\SubscribedChannels;

class NotificationsPage extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static string $view = 'jw-notifications::filament.pages.notifications-page';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'notifications';

    public function getTitle(): string|Htmlable
    {
        return __('jw-notifications::general.page.title');
    }

    public function mount()
    {
        $this->getNotifications();
    }

    public function getNotifications()
    {
        $subscribed = Auth::user()->subscribedNotificationChannels()->get()
            ->groupBy('notification')->map(fn ($i) => $i->pluck('channel')->toArray())->toArray();
        foreach (filament('jw-notifications')->notifications as $notification => $sct) {
            if (isset($subscribed[$notification])) {
                $section[$notification] = $subscribed[$notification];
            } else {
                $section[$notification] = [];
            }
        }
        $this->data = $section ?? [];
    }

    public function submit()
    {
        $notifications = $this->form->getState();
        $dump          = [];
        foreach ($notifications as $notification => $channels) {
            if (class_exists($notification)) {
                DB::transaction(function () use ($notification, $channels, &$dump) {
                    SubscribedChannels::where([
                        ['user_id', auth()->id()],
                        ['notification', $notification],
                    ])->update(['is_subscribed' => false]);
                    $dump[$notification] = $channels;
                    foreach ($channels as $channel) {
                        SubscribedChannels::updateOrCreate([
                            'user_id'      => auth()->id(),
                            'notification' => $notification,
                            'channel'      => $channel,
                        ], [
                            'is_subscribed' => true,
                        ]);
                    }
                });
            }
        }
        Notification::make('subscription-updated')
            ->title(__('jw-notifications::general.subscription-updated.title'))
            ->body(__('jw-notifications::general.subscription-updated.body'))
            ->success()
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form->schema($this->getFormSchema())->statePath('data');
    }

    public function getFormSchema(): array
    {
        $section = [];
        if (array_values(filament('jw-notifications')->notifications) === filament('jw-notifications')->notifications) {
            foreach (filament('jw-notifications')->notifications as $notification) {
                $key = str($notification)->afterLast('\\')->camel()->toString();

                $notifications[] = ToggleButtons::make($notification)
                    ->label(__($key))
                    ->inlineLabel()
                    ->extraAttributes(['class' => '!flex !w-full'])
                    ->grouped()
                    ->colors(filament('jw-notifications')->channelColors)
                    ->options(filament('jw-notifications')->channelOptions)
                    ->multiple();
            }
            $section = $notifications;
        } else {
            foreach (filament('jw-notifications')->notifications as $notification => $sectionKey) {
                if ( ! isset($section[$sectionKey])) {
                    $section[$sectionKey] = Section::make('section-' . $sectionKey)
                        ->collapsible()
                        ->persistCollapsed()
                        ->compact()
                        ->heading(__(str($sectionKey)->toString()))
                        ->compact();
                }

                $key = str($notification)->afterLast('\\')->camel()->toString();

                $notifications[$sectionKey][] = ToggleButtons::make($notification)
                    ->label(__($key))
                    ->inlineLabel()
                    ->extraAttributes(['class' => '!flex !w-full'])
                    ->grouped()
                    ->colors(filament('jw-notifications')->channelColors)
                    ->options(filament('jw-notifications')->channelOptions)
                    ->multiple();
            }
            foreach ($notifications ?? [] as $sectionKey => $sectionNotifications) {
                $section[$sectionKey]->schema($sectionNotifications);
            }
        }

        return $section;
    }
}
