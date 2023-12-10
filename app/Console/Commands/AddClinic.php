<?php

namespace App\Console\Commands;

use App\Domain\Clinic\AddClinicAction;
use App\Domain\Clinic\ClinicStatus;
use Illuminate\Console\Command;

class AddClinic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-clinic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('What is the name of the clinic?');
        $address = $this->ask('What is the address of the clinic?');
        $phone = $this->ask('What is the phone of the clinic?');
        $email = $this->ask('What is the email of the clinic?');
        $website = $this->ask('What is the website of the clinic?');
        $logo = '';
        $description = $this->ask('What is the description of the clinic?');

        $status = $this->choice(
            'What is the status of the clinic?',
            array_map(
                fn (ClinicStatus $status) => $status->value,
                ClinicStatus::cases()
            ),
        );

        dump($status);

        $this->info('You are about to add a clinic with the following details:');
        $this->table(
            ['Name', 'Address', 'Phone', 'Email', 'Website', 'Logo', 'Description', 'Status'],
            [[$name, $address, $phone, $email, $website, $logo, $description, $status]]
        );

        if ($this->confirm('Do you wish to continue?')) {
            $this->info('Adding clinic...');

            try {
                $payload = new \App\Domain\Clinic\AddClinicDTO(
                    $name,
                    $address,
                    $phone,
                    $email,
                    $website,
                    $logo,
                    $description,
                    $status
                );
            } catch (\Exception $e) {
                $this->error($e->getMessage());

                return;
            }

            /** @var AddClinicAction $action */
            $action = app(\App\Domain\Clinic\AddClinicAction::class);
            $clinic = $action->execute($payload);
            $this->info('Clinic added!');
            $this->info('Clinic ID: '.$clinic->id);
        } else {
            $this->info('Aborting...');
        }
    }
}
