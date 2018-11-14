<?php declare(strict_types=1);

namespace TracyClean\Bridges\Nette;

use Nette\DI\CompilerExtension;
use TracyClean\Bridges\Tracy\Panel;


/**
 * Class Extension
 *
 * @author  geniv
 * @package TracyClean\Bridges\Nette
 */
class Extension extends CompilerExtension
{
    /** @var array default values */
    private $defaults = [
        'tracyClean'   => true,
        'disableDebug' => true,
        'error404'     => true,
        'error500'     => true,
        'error503'     => true,
        'maintenance'  => true,
        'roles'        => [],
        'link'         => [],
    ];


    /**
     * Before Compile.
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        $panel = $builder->addDefinition($this->prefix('panel'))
            ->setFactory(Panel::class, [$config]);

        // linked panel to tracy
        $builder->getDefinition('tracy.bar')
            ->addSetup('addPanel', [$panel]);
    }
}
