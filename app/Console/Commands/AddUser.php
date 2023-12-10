<?php

namespace App\Console\Commands;

use App\Domain\Clinic\AddUserAction;
use App\Repository\ClinicRepository;
use Illuminate\Console\Command;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user to Clinic';

    /**
     * Execute the console command.
     */
    public function handle(ClinicRepository $clinicRepository)
    {
        //select clinic from all available clinics
        $clinicsOptions = array_map(fn ($clinic) => "[{$clinic->id}] ".$clinic->name, \App\Models\Clinic::query()->get()->all());
        $clinicChoice = $this->choice('Select clinic', $clinicsOptions);

        // get id from response string
        $clinicId = (int) substr($clinicChoice, 1, strpos($clinicChoice, ']') - 1);

        $name = $this->ask('What is the name of the user?');
        $email = $this->ask('What is the email of the user?');
        $phone = $this->ask('What is the phone of the user?');

        //validate input
        $this->validate(compact('name', 'email', 'phone'), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $this->info('You are about to add a user with the following details:');
        $this->table(
            ['Name', 'Email', 'Phone'],
            [[$name, $email, $phone]]
        );

        if ($this->confirm('Do you wish to continue?')) {
            $this->info('Adding user...');

            try {
                $payload = new \App\Domain\Clinic\AddUserDTO(
                    $name,
                    $email,
                    $phone,
                    $clinicId,
                );

                /** @var AddUserAction $action */
                $action = app(\App\Domain\Clinic\AddUserAction::class);
                $user = $action->execute($payload);

                $this->info('User added successfully!');
                $this->table(
                    ['Name', 'Email', 'Phone', 'Clinic'],
                    [[$user->name, $user->email, $user->phone, $user->clinic->name]]
                );
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    private function validate(array $payload, array $rules): void
    {
        $validator = \Illuminate\Support\Facades\Validator::make($payload, $rules);

        if ($validator->fails()) {

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            exit(1);
        }
    }
}
