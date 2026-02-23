<?php

namespace App\Traits;

trait TemplateParser
{
    public function replaceVariables($content, array $data = [])
    {
        if (empty($data)) {
            return $content;
        }

        foreach ($data as $key => $value) {
            $content = str_replace(
                '{' . $key . '}',
                $value,
                $content
            );
        }

        return $content;
    }
}
