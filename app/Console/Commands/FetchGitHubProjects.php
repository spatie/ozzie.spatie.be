<?php

namespace App\Console\Commands;

use App\GitHub\GitHub;
use App\Project;
use App\Projects;
use Illuminate\Console\Command;

class FetchGitHubProjects extends Command
{
    protected $signature = 'repositories:fetch';

    protected $description = 'Fetches the GitHub repositories.';

    public function handle()
    {
        $projects = app('mygithub')
            ->repositories(config('app.organization'))
            ->where('archived', false)
            ->map(function ($repo) {
                return [
                    'namespace' => $repo->namespace,
                    'name' => $repo->name,
                    'maintainers' => []
                ];
            });

        (new Projects)->write($projects);
    }
}
