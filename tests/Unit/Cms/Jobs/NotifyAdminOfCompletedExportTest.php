<?php

namespace Tests\Unit\Cms\Jobs;

use App\Models\Admin;
use Cms\Jobs\NotifyAdminOfCompletedExport;
use Cms\Notifications\DataExportCompleted;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminOfCompletedExportTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * The currently logged-in CMS Admin instance.
     *
     * @var Admin
     */
    protected Admin $admin;

    /**
     * The filename to store the exported data.
     *
     * @var string
     */
    protected string $filename;

    /**
     * The job instance, which being tested.
     *
     * @var NotifyAdminOfCompletedExport
     */
    protected NotifyAdminOfCompletedExport $job;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->actingAs($this->admin, config('cms.guard'));

        $this->filename = 'exported_datatable/setting_2021.xlsx';

        $this->job = new NotifyAdminOfCompletedExport($this->admin, $this->filename);
    }

    /** @test */
    public function it_can_send_the_notification_to_the_related_cms_admin()
    {
        Notification::fake();

        $this->job->handle();

        Notification::assertSentTo(
            [$this->admin],
            DataExportCompleted::class
        );
    }
}
