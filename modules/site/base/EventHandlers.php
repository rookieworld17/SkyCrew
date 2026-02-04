<?php

namespace modules\site\base;

use Craft;
use modules\site\web\assets\BaseAssetBundle;

class EventHandlers
{
    public static $classes = [];

    public function attachEventHandler(): void
    {
        $classes = $this->createMap(Craft::getAlias('@modules/site/eventHandlers/'));

        foreach ($classes as $class => $fileName) {
            $object = Craft::createObject($class);
            if($object instanceof BaseEventHandler) {
                Craft::$container->invoke([$object, 'attachEventHandlers']);
            }
        }
    }

    public function createMap($dir): array
    {
        if (is_string($dir)) {
            $dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        }
        $map = [];
        foreach ($dir as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $path = $file->getRealPath() ?: $file->getPathname();
            if ('php' !== pathinfo($path, PATHINFO_EXTENSION)) {
                continue;
            }

            $parts = explode(DIRECTORY_SEPARATOR, $path);
            $length = count($parts);
            if(!$length){
                continue;
            }

            $last = str_replace('.php', '', $parts[$length-1]);
            $map['modules\\site\\eventHandlers\\' . $last] = $path;
        }

        return $map;
    }
}