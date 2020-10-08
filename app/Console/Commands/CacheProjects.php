<?php

namespace App\Console\Commands;

use App\Project;
use App\Projects;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheProjects extends Command
{
    protected $signature = 'projects:cache';

    protected $description = 'Hydrates and caches all projects.';

    public function handle()
    {
        $this->output->progressStart((new Projects)->load()->count());

        $projects = (new Projects)->load()->sortBy('name')->map(function ($project) {
            $this->output->progressAdvance();

            return new Project(
                $project->namespace,
                $project->name,
                $project->maintainers
            );
        });

        $this->output->progressFinish();

        /*
         * We'll cache the projects slightly longer than the controller
         * to make sure the controller never has an expired value.
         */
        Cache::put('projects', $projects, 60 * 70);
    }
}
