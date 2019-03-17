<?php declare(strict_types=1);

namespace TracyClean\Bridges\Tracy;

use Exception;
use Latte\Engine;
use Nette\Application\Application;
use Nette\Bridges\ApplicationLatte\UIMacros;
use Nette\DI\Container;
use Nette\SmartObject;
use Tracy\IBarPanel;


/**
 * Class Panel
 *
 * @author  geniv
 * @package TracyClean\Bridges\Tracy
 */
class Panel implements IBarPanel
{
    use SmartObject;

    /** @var array */
    private $parameters;
    /** @var Container */
    private $container;


    /**
     * Panel constructor.
     *
     * @param array     $parameters
     * @param Container $container
     */
    public function __construct(array $parameters, Container $container)
    {
        $this->parameters = $parameters;
        $this->container = $container;
    }


    /**
     * Get tracy clean dirs.
     *
     * @return array
     */
    public function getTracyCleanDirs(): array
    {
        return $this->parameters['tracyCleanDirs'] ?? [];
    }


    /**
     * Renders HTML code for custom tab.
     *
     * @return string
     */
    public function getTab(): string
    {
        return '<span title="Tracy clean">' .
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16"><path d="M14.667 10.667H1.333V8c0-.735.598-1.333 1.333-1.333h3.349a.332.332 0 0 0 .31-.457A4.516 4.516 0 0 1 6 4.523V2c0-1.103.897-2 2-2s2 .897 2 2v2.523c.001.578-.11 1.15-.325 1.686a.335.335 0 0 0 .31.458h3.349c.735 0 1.333.598 1.333 1.333v2.667zM2 10h12V8a.667.667 0 0 0-.667-.667H9.985a1 1 0 0 1-.929-1.371c.184-.46.277-.944.277-1.439V2C9.333 1.265 8.735.667 8 .667S6.667 1.265 6.667 2v2.523c0 .495.093.979.277 1.439a1 1 0 0 1-.929 1.371H2.667A.667.667 0 0 0 2 8v2z"/><path d="M13.667 16H5.333A.332.332 0 0 1 5 15.669v-1.257l-.702 1.404A.333.333 0 0 1 4 16H2.333A.333.333 0 0 1 2 15.667v-5.333c0-.185.149-.334.333-.334h11.333c.185 0 .334.149.334.333v5.333a.333.333 0 0 1-.333.334zm-8-.667h7.667v-4.667H2.667v4.667h1.127l1.241-2.482a.334.334 0 1 1 .632.149v2.333z"/><path d="M12.333 16a.333.333 0 0 1-.333-.333v-3.333a.333.333 0 1 1 .666-.001v3.333a.332.332 0 0 1-.333.334zM11 16a.333.333 0 0 1-.333-.333V13a.333.333 0 1 1 .666 0v2.667A.333.333 0 0 1 11 16zm-1.333 0a.333.333 0 0 1-.333-.333V14A.333.333 0 1 1 10 14v1.667a.333.333 0 0 1-.333.333z"/><circle cx="8" cy="2" r=".667"/></svg>' .
            '</span>';
    }


    /**
     * Renders HTML code for custom panel.
     *
     * @return string
     */
    public function getPanel(): string
    {
        $application = $this->container->getByType(Application::class);    // load system application
        $presenter = $application->getPresenter();

        // catch trait problem
        try {
            $this->parameters['InternalTracyClean'] = ($presenter && $this->parameters['tracyClean'] ? $presenter->link('InternalTracyClean!') : null);
            $this->parameters['InternalDisableDebug'] = ($presenter && $this->parameters['disableDebug'] ? $presenter->link('InternalDisableDebug!') : null);
            $this->parameters['InternalError404'] = ($presenter && $this->parameters['error404'] ? $presenter->link('InternalError404!') : null);
            $this->parameters['InternalError500'] = ($presenter && $this->parameters['error500'] ? $presenter->link('InternalError500!') : null);
            $this->parameters['InternalError503'] = ($presenter && $this->parameters['error503'] ? $presenter->link('InternalError503!') : null);
            $this->parameters['InternalMaintenance'] = ($presenter && $this->parameters['maintenance'] ? $presenter->link('InternalMaintenance!') : null);
            $this->parameters['roles'] = ($presenter && $presenter->user->identity && $this->parameters['roles'] ? $this->parameters['roles'] : []);
            $this->parameters['rolesActive'] = ($presenter && $presenter->user->identity ? implode($presenter->user->identity->getRoles()) : null);
        } catch (Exception $e) {
        }

        $this->parameters['presenter'] = $presenter;

        // catch latte problem
        try {
            $latte = new Engine;
            $latte->addProvider('uiControl', $presenter);
            UIMacros::install($latte->getCompiler()); // n:href, {link }
            return $latte->renderToString(__DIR__ . '/PanelTemplate.latte', $this->parameters);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
