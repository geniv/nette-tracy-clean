<?php

namespace TracyClean;

use Exception;
use Nette\Http\IResponse;
use Nette\Utils\Finder;


/**
 * Trait TracyClean
 *
 * @author  geniv
 * @package TracyClean
 */
trait TracyClean
{

    /**
     * Handle internal tracy cleaner.
     */
    public function handleInternalTracyClean()
    {
        $dirs = [
            'temp/cache',
            'temp/sessions',
            'admin/temp/cache',
            'admin/temp/sessions',
        ];
        $path = $this->context->parameters['wwwDir'] . '/../';

        $itemCount = 0;
        foreach ($dirs as $dir) {
            if (file_exists($path . $dir)) {
                foreach (Finder::find('*')->from($path . $dir) as $item) {
                    if ($item->isFile() && unlink($item->getPathname())) {
                        $itemCount++;
                    }

                    if ($item->isDir() && @rmdir($item->getPath())) {
                        $itemCount++;
                    }
                }

            }
        }
        $this->flashMessage('Bylo smazáno ' . $itemCount . ' položek');
        $this->redirect('this');
    }


    /**
     * Handle internal disable debug.
     */
    public function handleInternalDisableDebug()
    {
        $fileDisableDebug = $this->context->parameters['appDir'] . '/../disable-debug';
        file_put_contents($fileDisableDebug, 'disable-debug');
        $this->flashMessage('Byl vypnut debug mod');
        $this->redirect('this');
    }


    /**
     * Handle internal error 404.
     */
    public function handleInternalError404()
    {
        $this->template->setFile($this->context->parameters['appDir'] . '/presenters/templates/Error/404.latte');
    }


    /**
     * Handle internal error 500.
     */
    public function handleInternalError500()
    {
        $this->template->setFile($this->context->parameters['appDir'] . '/presenters/templates/Error/500.phtml');
    }


    /**
     * Handle internal error 503.
     */
    public function handleInternalError503()
    {
        $this->template->setFile($this->context->parameters['appDir'] . '/presenters/templates/Error/503.phtml');
    }


    /**
     * Handle internal error maintenance.
     */
    public function handleInternalMaintenance()
    {
        $this->template->setFile($this->context->parameters['wwwDir'] . '/.maintenance.php');
    }
}
