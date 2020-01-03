<?php

namespace Orchestra\Canvas\Tests\Feature\Generators;

class NotificationTest extends TestCase
{
    protected $files = [
        'app/Notifications/FooNotification.php',
        'resources/views/foo-notification.blade.php',
    ];

    /** @test */
    public function it_can_generate_notification_file()
    {
        $this->artisan('make:notification', ['name' => 'FooNotification'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Notifications;',
            'class FooNotification extends Notification',
        ], 'app/Notifications/FooNotification.php');
    }

    /** @test */
    public function it_can_generate_notification_with_markdown_options_file()
    {
        $this->artisan('make:notification', ['name' => 'FooNotification', '--markdown' => 'foo-notification'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Notifications;',
            'class FooNotification extends Notification',
        ], 'app/Notifications/FooNotification.php');

        $this->assertFileContains([
            "@component('mail::message')",
        ], 'resources/views/foo-notification.blade.php');
    }
}
