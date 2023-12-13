<?php

/*
Plugin Name: Read Time Estimator
Plugin URI: https://example.com
Description: Estimates the reading time of a WordPress article and adds break points evenly within the article.
Version: 1.0.0
Author: Detective-Khalifah & Brian, with help from Bard
Author URI: https://example.com
*/

// Load the plugin dependencies.
//require_once(dirname(__FILE__) . '/vendor/autoload.php');

use Google\Cloud\TextToSpeech\TextToSpeechClient;

// Create a new class for the plugin.
class ReadTimeEstimator {

    // Constructor.
    public function __construct() {
        // Add a filter to estimate the reading time of an article.
        add_filter('the_content', array($this, 'estimateReadingTime'), 10, 1);

        // Add a filter to insert break points into an article.
        add_filter('the_content', array($this, 'insertBreakPoints'), 10, 1);
    }

    // Estimates the reading time of an article.
    public function estimateReadingTime($content) {
        // Use a text-to-speech library to estimate the reading time of the article.
	    $textToSpeech = new TextToSpeechClient();

        $readingTime = $textToSpeech->estimateReadingTime($content);

        // Return the reading time in minutes.
        return $readingTime / 60;
    }

    // Inserts break points into an article.
    public function insertBreakPoints($content) {
        // Get the estimated reading time of the article.
        $readingTime = $this->estimateReadingTime($content);

        // Calculate the number of break points.
        $numberOfBreakPoints = ceil($readingTime / 5);

        // Insert break points into the article.
        $content = preg_replace('/(<p>|<br \/>)/', '$1<div class="read-time-break-point"></div>', $content, $numberOfBreakPoints);

        // Return the updated content.
        return $content;
    }
}

// Create a new instance of the plugin class.
$readTimeEstimator = new ReadTimeEstimator();

function activate_readrex() {
	require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
}

// Activate the plugin.
register_activation_hook(__FILE__, array($readTimeEstimator, 'activate_readrex'));
