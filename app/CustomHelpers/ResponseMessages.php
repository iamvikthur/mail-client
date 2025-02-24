<?php

const CAMPAIGN_TEMPLATE_NOT_SET = "Campaign template not set";

const MCH_OTP_EXPIRED = "OTP expires after 10mins";
const MCH_INVALID_USER_EMAIL = "User with email address not found";
const MCH_INVALID_OTP = "OTP does not match. Invalid";
const MCH_USER_NOT_FOUND_BUT_BE_SILENT = "You will get an OTP if this email has been registered";
const MCH_OTP_SENT_MESSAGE = "Please enter OTP sent to your email address";
const MCH_SOMETHING_WENT_WRONG = "Something went wrong";
const MCH_ACCOUNT_VERIFIED = "Account verified successfully";
const MCH_ACCOUNT_VERIFICATION_LINK_SENT = "Email verification mail sent";
const MCH_EMAIL_ALREADY_VERIFIED = "Your email is already verified.";
const MCH_AUTH_USER_RETRIEVED = "Authenticated user retrieved successfully";
const MCM_SIGN_IN_SUCCESS = "Sign in successful";
const MCM_SIGN_OUT_SUCCESS = "Sign out successful";
const MCH_PASSWORD_RESET_SUCCESS = "Password reset successful";
const MCH_ACCOUNT_CREATION_SUCCESS = "Account created successfully";
const MCH_UNAUTHENTICATED = "You are unauthenticated. Please sign in";
const MCH_ACTION_UNAUTHORIZED = "Action unauthorized";
const MCH_ACCOUNT_DELETE_OTP_MESSAGE = "To complete your action, enter the one time password sent to your email address";
const MCH_EMAIL_NOT_VERIFIED = "Your email address is not verified.";


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

if (!function_exists('MCH_oneTimePasswordCacheKey')) {
    function MCH_oneTimePasswordCacheKey(string $email)
    {
        return "email-verification-key-$email";
    }
}
