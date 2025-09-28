<?php
session_start();
include('config/database.php');
include('functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // collect data
    $userID     = isset($_POST['userID']) ? intval($_POST['userID']) : null;
    $firstName  = trim($_POST['firstName'] ?? '');
    $lastName   = trim($_POST['lastName'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $subject    = trim($_POST['subject'] ?? '');
    $message    = trim($_POST['message'] ?? '');

    // basic validation
    if (empty($subject) || empty($message)) {
        $_SESSION['message'] = "Subject and message are required.";
        $_SESSION['message_type'] = "danger";
        header("Location: contact.php");
        exit();
    }

    // if guest user, require name + email
    if (!$userID && (empty($firstName) || empty($lastName) || empty($email))) {
        $_SESSION['message'] = "Please fill in your name and email.";
        $_SESSION['message_type'] = "danger";
        header("Location: contact.php");
        exit();
    }

    // prepare data
    $data = [
        'userID'    => $userID ?: null,
        'FirstName' => $firstName,
        'LastName'  => $lastName,
        'email'     => $email,
        'subject'   => $subject,
        'message'   => $message
    ];

    // save to DB
    if (saveContactMessage($data)) {
        $_SESSION['message'] = "Your message has been sent successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Something went wrong. Please try again.";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: contact.php");
    exit();
} else {
    header("Location: contact.php");
    exit();
}
