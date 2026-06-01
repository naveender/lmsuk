<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterUsernameController extends Controller
{
    /**
     * Check if username is unique and return suggestions if not.
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_.-]+$/',
            ],
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        if ($validator->fails()) {
            // Generate suggestions if we have a name, even if username validation fails
            $baseName = $request->input('name') ?: $request->input('username') ?: 'user';
            $suggestions = $this->generateSuggestions($baseName);

            return response()->json([
                'available' => false,
                'message' => $validator->errors()->first('username') ?: 'Invalid username format.',
                'suggestions' => $suggestions,
            ], 200); // Return 200 to handle error gracefully on client side
        }

        $username = strtolower($request->input('username'));
        $exists = User::where('username', $username)->exists();

        if ($exists) {
            $suggestions = $this->generateSuggestions($username);
            return response()->json([
                'available' => false,
                'message' => 'Username is already taken.',
                'suggestions' => $suggestions,
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Username is available!',
        ]);
    }

    /**
     * Generate 3 unique username suggestions.
     */
    public function generateSuggestions(string $base): array
    {
        // Clean base name: lowercase, convert spaces to dot/dash/underscore, remove special characters
        $base = strtolower($base);
        $base = preg_replace('/[^a-z0-9_.-]/', '', $base);
        
        // Remove duplicate separators
        $base = preg_replace('/[._-]{2,}/', '_', $base);
        $base = trim($base, '._-');

        if (empty($base) || strlen($base) < 3) {
            $base = 'user';
        }

        $suggestions = [];
        $attempts = 0;
        
        // Formats for suggestions
        $formats = [
            fn($b, $rand) => $b . $rand,
            fn($b, $rand) => $b . '_' . $rand,
            fn($b, $rand) => $b . '.' . $rand,
            fn($b, $rand) => $rand . $b,
            fn($b, $rand) => $b . 'lms' . $rand,
            fn($b, $rand) => $b . 'learn',
        ];

        while (count($suggestions) < 3 && $attempts < 50) {
            $attempts++;
            $rand = random_int(10, 9999);
            // Cycle through formats
            $formatIndex = count($suggestions) % count($formats);
            $candidate = $formats[$formatIndex]($base, $rand);
            
            // Ensure length is valid
            $candidate = substr($candidate, 0, 30);

            if (!in_array($candidate, $suggestions) && strlen($candidate) >= 3) {
                // Check database uniqueness
                if (!User::where('username', $candidate)->exists()) {
                    $suggestions[] = $candidate;
                }
            }
        }

        // Fallbacks if we can't find enough unique suggestions
        while (count($suggestions) < 3) {
            $candidate = $base . '_' . Str::random(5);
            $candidate = strtolower(substr($candidate, 0, 30));
            if (!User::where('username', $candidate)->exists() && !in_array($candidate, $suggestions)) {
                $suggestions[] = $candidate;
            }
        }

        return $suggestions;
    }
}
