<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Traits\TemplateParser;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable , Queueable , TemplateParser;

    protected $email;
    protected $subject;
    protected $body;
    protected $variables;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $subject, $body, $variables = [])
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->body = $body;
        $this->variables = $variables;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $subject = $this->replaceVariables($this->subject, $this->variables);
        $body = $this->replaceVariables($this->body, $this->variables);

        Mail::html($body, function ($message) use ($subject) {
            $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($this->email)
                    ->subject($subject);
        });
    }
}
