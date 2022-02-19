<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
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
        // Log::info($this->data);
        // Log::info($this->data["shop_name"]);
        $send_data = $this->data;
        Mail::send('jwt.emails.reminder_send', $send_data, function ($message) use ($send_data) {
            $message
                ->to($send_data["email"])
                ->from(config('mail.from.address'))
                ->subject("本日は". $send_data["shop_name"]."の予約日です");
        });
    }
}
