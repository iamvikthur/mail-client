<?php

if (!function_exists("MCH_model_created")) {
    function MCH_model_created(string $model = "Model")
    {
        return "$model created";
    }
}

if (!function_exists("MCH_model_updated")) {
    function MCH_model_updated(string $model = "Model")
    {
        return "$model updated";
    }
}

if (!function_exists("MCH_model_deleted")) {
    function MCH_model_deleted(string $model = "Model")
    {
        return "$model deleted";
    }
}

if (!function_exists("MCH_model_retrieved")) {
    function MCH_model_retrieved(string $model = "Model")
    {
        return "$model retrieved";
    }
}
