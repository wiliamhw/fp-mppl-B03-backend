<?php

namespace Cms\Jobs;

use App\Models\Admin;
use Cms\Notifications\DataExportCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyAdminOfCompletedExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The admin instance which should be notified.
     *
     * @var Admin
     */
    protected Admin $admin;

    /**
     * The filename which contains the exported data.
     *
     * @var string
     */
    protected string $filename;

    /**
     * Create a new job instance.
     *
     * @param Admin  $admin
     * @param string $filename
     */
    public function __construct(Admin $admin, string $filename)
    {
        $this->admin = $admin;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->admin->notify(new DataExportCompleted($this->filename));
    }
}
