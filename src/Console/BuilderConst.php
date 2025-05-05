<?php

namespace Arman\LaravelBuilder\Console;

use App\Dto\User\Admin\AdminStoreData;
use App\Services\Domain\User\Admin\AdminService;
use Illuminate\Console\Command;

class BuilderConst extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'builder:const';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate constants';


    /**
     * Execute the console command.
     */
    public function handle(
        AdminService $adminService
    ) {
        $name = $this->ask('Please enter your name');
        $username = $this->ask('Please enter the username');
        $password = $this->secret('Please enter the password');


        $adminService->store(new AdminStoreData($name, $username, $password));
    }
}
