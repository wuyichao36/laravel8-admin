<?php
// +----------------------------------------------------------------------
// | Date: 2021/5/18 16:37
// +----------------------------------------------------------------------
if( !function_exists("xssFilter") ) {
    function xssFilter($data)
    {
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
        }

        $attributes = $data->getAttributes();
        foreach ($attributes as &$v) {
            if (is_string($v)) {
                $v = htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
            }
        }
        $data->setRawAttributes($attributes);
    }
}
