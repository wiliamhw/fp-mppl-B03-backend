<?php

namespace Tests\Unit\Cms\Notifications;

use Cms\Notifications\DataExportCompleted;
use Tests\TestCase;

class DataExportCompletedTest extends TestCase
{
    /**
     * The filename to store the exported data.
     *
     * @var string
     */
    protected string $filename;

    /**
     * The notification instance, which being tested.
     *
     * @var DataExportCompleted
     */
    protected DataExportCompleted $notification;

    /**
     * Set up the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->filename = 'exported_datatable/setting_2021.xlsx';

        $this->notification = new DataExportCompleted($this->filename);
    }

    /** @test */
    public function it_can_generate_mail_message_automatically()
    {
        $message = $this->notification->toMail();

        $expected = [
            'level'      => 'info',
            'subject'    => null,
            'greeting'   => null,
            'salutation' => null,
            'introLines' => [
                'The datatable export process has been completed. You may now download the file by pressing the button below.',
            ],
            'outroLines' => [
                'Thank you for using our CMS.',
            ],
            'actionText'           => 'Download',
            'actionUrl'            => 'http://localhost/storage/exported_datatable/setting_2021.xlsx',
            'displayableActionUrl' => 'http://localhost/storage/exported_datatable/setting_2021.xlsx',
        ];
        $actual = $message->toArray();

        self::assertEquals($expected, $actual);
    }
}
