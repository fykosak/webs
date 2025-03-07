<?php

declare(strict_types=1);

namespace App\Components\PdfGallery;

use Fykosak\Utils\Components\DIComponent;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\DI\Container;
use Nette\Utils\Finder;
use Nette\Utils\UnknownImageFileException;

class PdfGalleryControl extends DIComponent
{
    private readonly string $wwwDir;
    private readonly Cache $cache;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->wwwDir = $container->getParameters()['wwwDir'];
    }

    public function injectStorage(Storage $storage): void
    {
        $this->cache = new Cache($storage, __NAMESPACE__);
    }

    public static function getPdfs(string $path, string $wwwDir, bool $fromMetadata = false): array
    {
        $pdfs = [];
        try {
            $iterator = Finder::findFiles('*.pdf')->in($wwwDir . $path)->getIterator();
        } catch (\Exception $e) {
            return [];
        }

        foreach ($iterator as $file) {
            if ($fromMetadata) {
                try {
                    $info = static::getPdfProp($file->getRealPath());
                    $name = $info["Title"] ?? ($info["title"] ?? '');
                } catch (\Exception $e) {
                    $name = '';
                }
            } else {
                $name = null;
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

        foreach ($pdfs as $index => &$pdfFile) {
            $pdfFile['index'] = $index;
        }

        return $pdfs;
    }

    /**
     * @throws UnknownImageFileException
     * @throws \Throwable
     */
    public function render(string $path, ?string $style = null): void
    {
        $style ??= "List";
        $renderFile = __DIR__ . DIRECTORY_SEPARATOR . 'pdfGallery' . $style . '.latte';

        $this->template->pdfs = $this->cache->load(
            [$path, $this->wwwDir],
            fn() => self::getPdfs($path, $this->wwwDir)
        );
        $this->template->render($renderFile);
    }
    public function renderFromMetadata(string $path, ?string $style = null): void
    {
        $style ??= "List";
        $renderFile = __DIR__ . DIRECTORY_SEPARATOR . 'pdfGallery' . $style . '.latte';

        $this->template->pdfs = $this->cache->load(
            [$path, $this->wwwDir],
            fn() => self::getPdfs($path, $this->wwwDir, true)
        );
        $this->template->render($renderFile);
    }

    /**
     * @throws UnknownImageFileException
     * @throws \Throwable
     */
    public function hasFiles(string $path): bool
    {
        return count($this->cache->load(
            [$path, $this->wwwDir],
            fn() => self::getPdfs($path, $this->wwwDir)
        )) > 0;
    }

    /**
     * magic from http://www.fpdf.org/en/script/script59.php
     * @param string $file filename
     * @return array whose keys are the names of the properties found in the file
     */
    public static function getPdfProp($file): array
    {
        $f = fopen($file, 'rb');
        if (!$f) {
            return [];
        }
        //Read the last 16KB
        fseek($f, -16384, SEEK_END);
        $s = fread($f, 16384);

        //Extract cross-reference table and trailer
        if (!preg_match("/xref[\r\n]+(.*)trailer(.*)startxref/s", $s, $a)) {
            return [];
        }
        $xref = $a[1];
        $trailer = $a[2];

        //Extract Info object number
        if (!preg_match('/Info ([0-9]+) /', $trailer, $a)) {
            return [];
        }
        $object_no = (int) $a[1];

        //Extract Info object offset
        $lines = preg_split("/[\r\n]+/", $xref);
        $line = $lines[1 + $object_no];
        $offset = (int) $line;
        if ($offset == 0) {
            return [];
        }
        //Read Info object
        fseek($f, $offset, SEEK_SET);
        $s = fread($f, 1024);
        fclose($f);

        //Extract properties
        if (!preg_match('/<<(.*)>>/Us', $s, $a)) {
            return [];
        }
        $n = preg_match_all('|/([a-z]+) ?\((.*)\)|Ui', $a[1], $a);
        $prop = array();
        for ($i = 0; $i < $n; $i++) {
            $prop[$a[1][$i]] = $a[2][$i];
        }
        return $prop;
    }
}
