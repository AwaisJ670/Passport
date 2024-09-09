<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Dialogflow\V2\SessionsClient;

class DialogflowController extends Controller
{
    public function detectIntent(Request $request)
    {
        $projectId = 'uos-it-styy'; // Replace with your Dialogflow project ID
        $sessionId = session()->getId(); // Unique session ID for each user
        $text = $request->input('query'); // User input (marks) from the form

        // Path to your service account key JSON file
        $credentials = storage_path('app/uos-it-styy-72901fc26294.json');

        // Initialize Dialogflow client
        $sessionsClient = new SessionsClient([
            'credentials' => $credentials,
        ]);
        // Initialize Dialogflow client
        $sessionsClient = new SessionsClient([
            'credentials' => $credentials,
        ]);
        $session = $sessionsClient->sessionName($projectId, $sessionId);

        // Create text input
        $textInput = new \Google\Cloud\Dialogflow\V2\TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode('en');

        // Create query input
        $queryInput = new \Google\Cloud\Dialogflow\V2\QueryInput();
        $queryInput->setText($textInput);

        // Send the request
        $response = $sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();

        // Get the response text from Dialogflow
        $fulfillmentText = $queryResult->getFulfillmentText();

        // Handle logic for checking marks eligibility
        $marks = $this->extractMarks($text); // Extract marks from user input
        if ($marks !== null) {
            $eligibilityResponse = $this->checkEligibility($marks);
            return response()->json(['response' => $eligibilityResponse]);
        }

        // Return the original Dialogflow response
        return response()->json(['response' => $fulfillmentText]);
    }
    private function extractMarks($text)
    {
        preg_match('/\b\d+\b/', $text, $matches);
        return isset($matches[0]) ? (int)$matches[0] : null;
    }

    // Helper function to check eligibility based on marks
    private function checkEligibility($marks)
    {
        // Check if the marks are within the valid range (e.g., 0-1100)
        if ($marks < 0 || $marks > 1100) {
            return "The marks you entered are invalid. Please enter a value between 0 and 1100.";
        }

        // Check eligibility based on valid marks
        if ($marks >= 800) {
            return "Congratulations, you are eligible for merit.";
        } else {
            return "Unfortunately, you are not eligible for merit.";
        }
    }
}
