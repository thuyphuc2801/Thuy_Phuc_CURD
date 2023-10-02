<?php
function validateField($request, $key)
{
    return isset($request[$key]) && $request[$key] != "" ? "" : "$key is required";
}
function validate($request, $keys)
{
    $results = [];
    foreach ($keys as $key) {
        $error = validateField($request, $key);
        if ($error != "") {
            $results[$key] = $error;
        }
    }
    return $results;
}
