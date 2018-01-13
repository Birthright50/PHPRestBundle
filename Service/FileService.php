<?php
/**
 * Created by PhpStorm.
 * User: birthright
 * Date: 13.01.18
 * Time: 2:00
 */

namespace Birthright\SuperRestBundle\Service;

use zpt\anno\Annotations;

class FileService
{
    private $servicesPath;

    public function __construct(string $servicesPath)
    {
        $this->servicesPath = $servicesPath;
    }

    public function findService($entity) :string
    {
        $fullClassName = null;

        foreach (glob($this->servicesPath . '/*.*') as $file) {
            $fp = fopen($file, 'r');
            $class = $namespace = $buffer = '';
            $i = 0;
            while (!$class) {
                if (feof($fp)) break;
                $buffer .= fread($fp, 512);
                $tokens = token_get_all($buffer);
                if (strpos($buffer, '{') === false) continue;
                for (; $i < count($tokens); $i++) {
                    if ($tokens[$i][0] === T_NAMESPACE) {
                        for ($j = $i + 1; $j < count($tokens); $j++) {
                            if ($tokens[$j][0] === T_STRING) {
                                $namespace .= '\\' . $tokens[$j][1];
                            } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                                break;
                            }
                        }
                    }
                    if ($tokens[$i][0] === T_CLASS) {
                        for ($j = $i + 1; $j < count($tokens); $j++) {
                            if ($tokens[$j] === '{') {
                                $class = $tokens[$i + 2][1];
                            }
                        }
                    }
                }
            }
            $className = $namespace . '\\' . $class;
            $needToSkip = $this->checkServicePath($className, $entity);
            if ($needToSkip) {
                $fullClassName = $className;
                break;
            }
        }
        if (is_null($fullClassName)) {
            throw new \InvalidArgumentException();
        }
        return ltrim($fullClassName, '\\');
    }

    private function checkServicePath(string $fullClassName, string $entity): bool
    {
        $class = new \ReflectionClass($fullClassName);
        $annotation = 'servicerestresource';
        $classAnnotations = new Annotations($class);
        if ($classAnnotations->isAnnotatedWith('ServiceRestResource')) {
            $urlPath = $classAnnotations->asArray()[$annotation]['path'];
            if ($urlPath === $entity) {
                return $class->implementsInterface('Birthright\SuperRestBundle\Service\RestService');
            }
        }
        return false;
    }
}