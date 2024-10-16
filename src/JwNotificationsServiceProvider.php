<?php

namespace JohnWink\JwNotifications;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use JohnWink\JwNotifications\Commands\JwNotificationsCommand;
use JohnWink\JwNotifications\Testing\TestsJwNotifications;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class JwNotificationsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'jw-notifications';

    public static string $viewNamespace = 'jw-notifications';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('john-wink/jw-notifications');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/jw-notifications/{$file->getFilename()}"),
                ], 'jw-notifications-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsJwNotifications());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'john-wink/jw-notifications';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            JwNotificationsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_jw_notifications_table',
        ];
    }
}
