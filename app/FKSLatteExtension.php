<?php
namespace App;

use Latte\Compiler;
use Latte\Engine;
use Latte\Runtime\FilterInfo;


final class FKSLatteExtension
{

    static function register(Compiler $compiler)
    {
        print_r($compiler->getFilters());
        die();
    }

    static function filterTexParagraphs(FilterInfo $info, ?string $text): string
    {
        if (!in_array($info->contentType, [null, Engine::CONTENT_TEXT])) {
            throw new \Exception("Filter |texParagraphs used in incompatible content type $info->contentType.");
        }
        if ($text == null)
            return null;
        $arr = mb_split("\n\n", $text);
        foreach ($arr as $key => $value) {
            $value = trim($value);
            if ($value === '')
                unset($arr[$key]);
            else
                $arr[$key] = "<p>$value</p>";
        }
        $info->contentType = Engine::CONTENT_HTML;
        return implode('', $arr);
    }

}
