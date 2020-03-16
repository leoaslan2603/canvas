<?php

namespace Orchestra\Canvas\Commands;

use Orchestra\Canvas\Core\Commands\Command;
use Orchestra\Canvas\Core\Presets\Package;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StubPublish extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'stub:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all stubs that are available for customization';

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this->setName($this->name)
            ->setDescription($this->description);
    }

    /**
     * Execute the command.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $this->preset->filesystem();
        $stubsPath = \sprintf('%s/stubs', $this->preset->basePath());

        if (! $files->isDirectory($stubsPath)) {
            $files->makeDirectory($stubsPath);
        }

        $files = [
            \realpath(__DIR__.'/../../storage/job/job.queued.stub') => $stubsPath.'/job.queued.stub',
            \realpath(__DIR__.'/../../storage/job/job.stub') => $stubsPath.'/job.stub',
            \realpath(__DIR__.'/../../storage/database/eloquent/model.pivot.stub') => $stubsPath.'/model.pivot.stub',
            \realpath(__DIR__.'/../../storage/database/eloquent/model.stub') => $stubsPath.'/model.stub',
            \realpath(__DIR__.'/../../storage/laravel/request.stub') => $stubsPath.'/request.stub',
            $this->getFeatureTestStubFile() => $stubsPath.'/test.stub',
            \realpath(__DIR__.'/../../storage/testing/test.unit.stub') => $stubsPath.'/test.unit.stub',
            \realpath(__DIR__.'/../../storage/database/migrations/migration.create.stub') => $stubsPath.'/migration.create.stub',
            \realpath(__DIR__.'/../../storage/database/migrations/migration.stub') => $stubsPath.'/migration.stub',
            \realpath(__DIR__.'/../../storage/database/migrations/migration.update.stub') => $stubsPath.'/migration.update.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.api.stub') => $stubsPath.'/controller.api.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.invokable.stub') => $stubsPath.'/controller.invokable.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.model.api.stub') => $stubsPath.'/controller.model.api.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.model.stub') => $stubsPath.'/controller.model.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.nested.api.stub') => $stubsPath.'/controller.nested.api.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.nested.stub') => $stubsPath.'/controller.nested.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.plain.stub') => $stubsPath.'/controller.plain.stub',
            \realpath(__DIR__.'/../../storage/routing/controller.stub') => $stubsPath.'/controller.stub',
        ];

        foreach ($files as $from => $to) {
            \file_put_contents($to, \file_get_contents($from));
        }

        $this->info('Stubs published successfully.');

        return 0;
    }

    /**
     * Get feature test stub file.
     */
    protected function getFeatureTestStubFile(): string
    {
        if ($this->preset instanceof Package) {
            return \realpath(__DIR__.'/../../storage/testing/test.package.stub');
        }

        return \realpath(__DIR__.'/../../storage/testing/test.stub');
    }
}