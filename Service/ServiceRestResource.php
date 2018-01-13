<?php
/**
 * Created by PhpStorm.
 * User: birthright
 * Date: 13.01.18
 * Time: 1:38
 */

namespace Birthright\SuperRestBundle\Service;


/**
 * @Annotation
 * @Target("CLASS")
 */
class ServiceRestResource
{
    /**
     * @Required
     *
     * @var string
     */
    public $path;

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}