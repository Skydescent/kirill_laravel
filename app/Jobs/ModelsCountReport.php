<?php

namespace App\Jobs;

use App\Events\ReportGenerated as EventReportGenerated;
use App\Mail\ReportGenerated as MailReportGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class ModelsCountReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected array $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $report = [];
        foreach ($this->data['report_fields'] as  $field) {
            $report[] = [
                'title' => $field['title'],
                'value' => $field['data']::count(),
            ];
        }
        event(new EventReportGenerated($report, $this->data['user']));

        Mail::to($this->data['user']->email)->send(
            new MailReportGenerated($report)
        );
    }
}
