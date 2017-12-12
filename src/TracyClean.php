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
     * Prepare file.
     *
     * @param $source
     * @return bool|null|string
     */
    private function prepareFile($source)
    {
        $fileTempNam = tempnam(sys_get_temp_dir(), 'TracyClean');
        if (copy($source, $fileTempNam)) {
            $content = token_get_all(file_get_contents($fileTempNam));
            $template = array_values(array_filter($content, function ($row) { return $row[0] == T_INLINE_HTML; }))[0][1];
            if (file_put_contents($fileTempNam, $template)) {
                return $fileTempNam;
            }
        }
        return null;
    }


    /**
     * Handle internal error 503.
     */
    public function handleInternalError503()
    {
        $this->template->setFile($this->prepareFile($this->context->parameters['appDir'] . '/presenters/templates/Error/503.phtml'));
    }


    /**
     * Handle internal maintenance.
     */
    public function handleInternalMaintenance()
    {
        $this->template->setFile($this->prepareFile($this->context->parameters['wwwDir'] . '/.maintenance.php'));
    }
}
