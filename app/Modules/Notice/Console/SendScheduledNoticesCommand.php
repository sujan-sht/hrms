<?php

namespace App\Modules\Notice\Console;

use App\Modules\Notice\Entities\Notice;
use App\Modules\Notice\Repositories\NoticeInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendScheduledNoticesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notices';

    protected $notice;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NoticeInterface $notice)
    {
        parent::__construct();
        $this->notice = $notice;
    }



    public function handle()
    {
        $now = Carbon::now();
        $notices = Notice::where('type', 2)
            ->where('notice_date', '<=', $now->toDateString())
            ->where('notice_time', '<=', $now->toTimeString())
            ->get();

        foreach ($notices as $notice) {
            // Send email notification
            $this->notice->sendMailNotification($notice);
            // Update notice status to sent
            $notice->type = 1;
            $notice->save();
        }

        $this->info('Scheduled notices sent successfully.');
    }
}
