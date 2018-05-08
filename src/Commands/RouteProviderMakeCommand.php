<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RouteProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'module';

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'module:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Create a new route service provider for the specified module.';

    /**
     * The command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $web = new Stub('/routes/web.stub', [
            'STUDLY_NAME'         => $this->getClassNamespace($module),
            'PLURAL_LOWER_NAME'       => Str::plural(strtolower($this->getClassNamespace($module))),
        ]);

        $web->render();

        $api = new Stub('/routes/api.stub', [
            'STUDLY_NAME'         => $this->getClassNamespace($module),
            'PLURAL_LOWER_NAME'       => Str::plural(strtolower($this->getClassNamespace($module))),
        ]);

        $api->render();

        $channel = new Stub('/routes/channels.stub', [
            'STUDLY_NAME'         => $this->getClassNamespace($module),
            'PLURAL_LOWER_NAME'       => Str::plural(strtolower($this->getClassNamespace($module))),
        ]);

        $channel->render();

        $provider = new Stub('/route-provider.stub', [
            'STUDLY_NAME'         => $this->getClassNamespace($module),
            'API_VERSION'       => $this->laravel['modules']->config('api.version'),
            'MODULE_NAMESPACE' => $this->laravel['modules']->config('namespace'),
        ]);

        $provider->render();

        return compact('web', 'api', 'channel', 'provider');
    }
}
