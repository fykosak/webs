<?php

declare(strict_types=1);

namespace App\Components\PdfGallery;

use Fykosak\Utils\Components\DIComponent;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\DI\Container;
use Nette\Utils\Finder;
use Nette\Utils\UnknownImageFileException;
use Smalot\PdfParser\Config;
use Smalot\PdfParser\Parser;

class PdfGalleryControl extends DIComponent
{
    private readonly string $wwwDir;
    private readonly Cache $cache;
    private bool|string $notRenderToStr = true;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }

    public function injectStorage(Storage $storage): void
    {
        $this->cache = new Cache($storage, __NAMESPACE__);
    }

    public static function getPdfs(string $path, string $wwwDir): array
    {
        $pdfs = [];
        try {
            $iterator = Finder::findFiles('*.pdf')->in($wwwDir . $path)->getIterator();
        } catch (\Exception $e) {
            return [];
        }

        $parser = new Config();
        $parser->setRetainImageContent(false);
        $parser = new Parser(config: $parser);


        foreach ($iterator as $file) {
            try {
                $info = $parser->parseFile($file->getRealPath())->getDetails();
                $name = $info["Title"] ?? ($info["title"] ?? '');
            } catch (\Exception $e) {
                $name = '';
            }

            $wwwPath = substr($file->getPathname(), strlen($wwwDir));
            $pdfs[] = [
                'src' => $wwwPath,
                'name' => $name ?: $file->getBasename('.pdf'),
            ];
        }

        //sort alphabetically
        usort($pdfs, function ($a, $b) {
            return $a['name'] <=> $b['name'];
        });

        foreach ($pdfs as $index => &$pdffile) {
            $pdffile['index'] = $index;
        }

        return $pdfs;
    }

    /**
     * @throws UnknownImageFileException
     * @throws \Throwable
     */
    public function render(?string $path = null, ?string $style = null): void
    {
        if ($style === null) {
            $style = $path===null ? "List" : $style = "Buttons";
        }
        $renderFile = __DIR__ . DIRECTORY_SEPARATOR . 'pdfGallery' . $style . '.latte';

        $path ??= $this->wwwDir . strtolower(str_replace(':', '/', $this->getPresenter()->getAction(true)));

        $this->template->pdfs = $this->cache->load(
            [$path, $this->wwwDir],
            fn() => self::getPdfs($path, $this->wwwDir)
        );
        if ($this->notRenderToStr == true) {
            $this->template->render($renderFile);
            return;
        }
        $this->notRenderToStr = $this->template->renderToString($renderFile);
    }

    public function renderToString(?string $path = null, string $style = "default"): string
    {
        $this->notRenderToStr = false;
        $this->render($path, $style);
        if (is_bool($this->notRenderToStr)) {
            throw new \Exception('Component ' . __CLASS__ . ' did not render properly!');
        }
        return $this->notRenderToStr;
    }

    /**
     * @throws UnknownImageFileException
     * @throws \Throwable
     */
    public function hasFiles(?string $path = null): bool
    {
        $path ??= $this->wwwDir . strtolower(str_replace(':', '/', $this->getPresenter()->getAction(true)));

        return count($this->cache->load(
            [$path, $this->wwwDir],
            fn() => self::getPdfs($path, $this->wwwDir)
        )) > 0;
    }
}
