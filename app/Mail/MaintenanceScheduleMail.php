<?php
namespace App\Mail;

use App\Models\MaintenanceSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaintenanceScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    public function __construct(MaintenanceSchedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function build()
    {
        return $this->subject('📅 Maintenance Schedule Notification')
                    ->view('emails.maintenance-schedule')
                    ->with([
                        'schedule' => $this->schedule
                    ]);
    }

}

