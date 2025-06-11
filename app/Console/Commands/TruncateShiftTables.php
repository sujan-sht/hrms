<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\NewShift\Entities\NewShiftEmployee;
use App\Modules\NewShift\Entities\NewShiftEmployeeDetail;

class TruncateShiftTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'new:shift:truncate';

    protected $description = 'Delete all records from NewShiftEmployee and NewShiftEmployeeDetail with progress';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
    $this->info('Starting deletion of NewShiftEmployeeDetail records...');

    $detailCount = NewShiftEmployeeDetail::count();

    if ($detailCount > 0) {
        $bar = $this->output->createProgressBar($detailCount);
        $bar->start();

        NewShiftEmployeeDetail::chunkById(500, function ($details) use ($bar) {
            foreach ($details as $detail) {
                $detail->delete();
                $bar->advance();
            }
        });

        $bar->finish();
        $this->line(''); // <- Here
    } else {
        $this->warn('No records found in NewShiftEmployeeDetail.');
    }

    $this->info('NewShiftEmployeeDetail records deleted.');

    $this->info('Starting deletion of NewShiftEmployee records...');

    $employeeCount = NewShiftEmployee::count();

    if ($employeeCount > 0) {
        $bar = $this->output->createProgressBar($employeeCount);
        $bar->start();

        NewShiftEmployee::chunkById(500, function ($records) use ($bar) {
            foreach ($records as $record) {
                $record->delete();
                $bar->advance();
            }
        });

        $bar->finish();
        $this->line(''); // <- And here
    } else {
        $this->warn('No records found in NewShiftEmployee.');
    }

    $this->info('NewShiftEmployee records deleted.');
}

}
