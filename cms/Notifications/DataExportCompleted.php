<?php

namespace Cms\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class DataExportCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The filename which contains the exported data.
     *
     * @var string
     */
    protected string $filename;

    /**
     * Create a new notification instance.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = Storage::disk(config('cms.datatable_export_disk'))->url($filename);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage())
                    ->line('The datatable export process has been completed. You may now download the file by pressing the button below.')
                    ->action('Download', $this->filename)
                    ->line('Thank you for using our CMS.');
    }
}
