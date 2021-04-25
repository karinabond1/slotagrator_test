<?php

namespace App\Console\Commands;

use App\Models\MoneyOperation;
use Illuminate\Console\Command;

class SendMoneyToUsersCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendMoneyToUsersCard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $getOperations = MoneyOperation::getAllOperationThatNeedToSend();
        foreach ($getOperations as $operation)
        {
            /**
             * @var MoneyOperation $operation
             */
            $operation->status = MoneyOperation::SENT_TO_BANK_CARD;
            $operation->save();
        }
        return true;
    }
}
