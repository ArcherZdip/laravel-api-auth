<?php

if (!function_exists('base64_urlsafe_encode')) {
    /**
     * Return the Base64-encoded version of $data, The alphabet uses '-' instead of '+' and '_' instead of '/'.
     *
     * @param $data
     *
     * @return mixed
     */
    function base64_urlsafe_encode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}

if (!function_exists('base64_urlsafe_decode')) {
    /**
     * Return the Base64-decoded version of $data, The alphabet uses '-' instead of '+' and '_' instead of '/'.
     *
     * @param $data
     * @param null $strict
     *
     * @return bool|string
     */
    function base64_urlsafe_decode($data, $strict = null)
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data), $strict);
    }
}
