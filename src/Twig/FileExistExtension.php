<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FileExistExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('file_exist', [$this, 'fileExist']),
        ];
    }

    /**
     * @param string path
     * @param boolean alternative path ; default value is set a TRUE
     * @return string Return the path
     */
    public function fileExist(string $value = null, bool $alternativePath = true)
    {
        if(empty($value) || !file_exists($this->container->getParameter("project_public_dir") . $value)) {
            if($alternativePath) {
                $value = "content/img/thumbnail.jpg";
            } else {
                $value = "";
            }
        }

        return $value;
    }
}
