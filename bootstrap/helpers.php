<?php
/**
 * 自定义辅助函数
 */

function route_class()
{
    return str_replace('.' , '-', Route::currentRouteName());
}

/**
 * 文章摘要
 *
 * @param $value        // 内容
 * @param int $length   // 长度
 * @return string
 */
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}