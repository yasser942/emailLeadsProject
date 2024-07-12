<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailToLead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lead;
    protected $emailTemplate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, EmailTemplate $emailTemplate)
    {
        $this->lead = $lead;
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send([], [], function ($message) {
            $message->to($this->lead->email)
                    ->subject($this->emailTemplate->subject)
                    ->html($this->emailTemplate->body);
        });
    }
}