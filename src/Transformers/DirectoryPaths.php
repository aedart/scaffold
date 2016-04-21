<?php namespace Aedart\Scaffold\Transformers;

/**
 * Directory Paths Transformer
 *
 * @author Alin Eugen Deac <aedart@gmail.com>
 * @package Aedart\Scaffold\Transformers
 */
class DirectoryPaths
{

    /**
     * Transform the given list of directories into a flat array
     * which contains directory "paths", separated via the OS
     * directory separator.
     *
     * @param array[] $directories List of directories
     * @param string $root [optional] Previous root directory
     *
     * @return array
     */
    static public function transform(array $directories = [], $root = '')
    {
        $structure = [];
        foreach($directories as $key => $value){
            if(is_array($value)){
                $structure = array_merge($structure, self::transform($value, $root . $key . DIRECTORY_SEPARATOR));
                continue;
            }

            $structure[] = $root . $value;
        }

        return $structure;
    }
    
}