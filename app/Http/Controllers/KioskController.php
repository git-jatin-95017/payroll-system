<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Checkin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KioskController extends Controller
{
    private function checkCompanyVerification()
    {
        if (!session('kiosk_company_id')) {
            return redirect()->route('kiosk.start');
        }
        return null;
    }

    public function showKiosk()
    {
        return view('kiosk.login');
    }

    public function verifyCompany(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string'
        ]);

        // Check if company exists in company_profile table
        $company = \App\Models\CompanyProfile::where('company_name', $request->company_name)->first();

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found. Please verify the company name.'
            ]);
        }

        // Store company ID in session for later use
        session(['kiosk_company_id' => $company->id]);

        return response()->json([
            'success' => true,
            'message' => 'Company verified successfully'
        ]);
    }

    public function showStart()
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }
        return view('kiosk.start');
    }

    public function initiateFaceRecognition()
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }
        return view('kiosk.face-recognition');
    }

    public function verifyFace(Request $request)
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }

        $request->validate([
            'face_data' => 'required|string', // Base64 image data
            'face_features' => 'required|array' // Extracted face features
        ]);

        // Log incoming feature vector size for debugging
        \Log::info('Incoming face features count: ' . count($request->face_features));

        // Get all employees created by the current company with face data
        $employees = User::where('created_by', session('kiosk_company_id'))
            ->whereHas('employeeProfile', function($query) {
                $query->whereNotNull('face_data');
            })
            ->with('employeeProfile')
            ->get();

        if ($employees->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No registered employees found with face data'
            ]);
        }

        // Find the best match by comparing face features
        $bestMatch = null;
        $highestSimilarity = 0;
        $threshold = 0.85; // Increased threshold for normalized coordinates
        $matchDetails = [];

        foreach ($employees as $employee) {
            try {
                $storedFaces = json_decode($employee->employeeProfile->face_data, true);
                if (!is_array($storedFaces)) {
                    \Log::warning("Invalid face data format for employee {$employee->id}");
                    continue;
                }

                foreach ($storedFaces as $index => $storedFace) {
                    // Skip if stored face data is invalid
                    if (!isset($storedFace['features']) || !is_array($storedFace['features'])) {
                        \Log::warning("Invalid features format for employee {$employee->id}, face index {$index}");
                        continue;
                    }

                    // Log feature vector sizes for comparison
                    \Log::debug("Comparing features - Input: " . count($request->face_features) . 
                              ", Stored: " . count($storedFace['features']) . 
                              " for employee {$employee->id}");

                    // Only compare if feature counts match
                    if (count($request->face_features) !== count($storedFace['features'])) {
                        \Log::warning("Feature count mismatch for employee {$employee->id} - Input: " . 
                                    count($request->face_features) . ", Stored: " . 
                                    count($storedFace['features']));
                        continue;
                    }

                    $similarity = $this->calculateCosineSimilarity(
                        $request->face_features,
                        $storedFace['features']
                    );

                    // Log similarity score for debugging
                    $matchDetails[] = [
                        'employee_id' => $employee->id,
                        'face_index' => $index,
                        'similarity' => $similarity,
                        'feature_counts' => [
                            'input' => count($request->face_features),
                            'stored' => count($storedFace['features'])
                        ]
                    ];

                    if ($similarity > $highestSimilarity) {
                        $highestSimilarity = $similarity;
                        $bestMatch = $employee;
                    }
                }
            } catch (\Exception $e) {
                \Log::error("Error processing face data for employee {$employee->id}: " . $e->getMessage());
                continue;
            }
        }

        // Log match details for debugging
        \Log::info('Face match details:', [
            'highest_similarity' => $highestSimilarity,
            'threshold' => $threshold,
            'match_details' => $matchDetails
        ]);

        if (!$bestMatch || $highestSimilarity < $threshold) {
            return response()->json([
                'success' => false,
                'message' => 'Face verification failed. No matching employee found.',
                'debug_info' => [
                    'highest_similarity' => $highestSimilarity,
                    'threshold' => $threshold,
                    'match_details' => $matchDetails
                ]
            ]);
        }

        // Store the matched user ID in session
        session(['kiosk_user_id' => $bestMatch->id]);

        return response()->json([
            'success' => true,
            'message' => 'Face verified',
            'user' => [
                'name' => $bestMatch->name,
                'photo' => $bestMatch->employeeProfile->file 
                    ? asset('files/' . $bestMatch->employeeProfile->file)
                    : null
            ]
        ]);
    }

    /**
     * Calculate cosine similarity between two feature vectors
     */
    private function calculateCosineSimilarity(array $features1, array $features2): float
    {
        if (count($features1) !== count($features2)) {
            return 0.0;
        }

        $dotProduct = 0;
        $norm1 = 0;
        $norm2 = 0;

        for ($i = 0; $i < count($features1); $i++) {
            $dotProduct += $features1[$i] * $features2[$i];
            $norm1 += $features1[$i] * $features1[$i];
            $norm2 += $features2[$i] * $features2[$i];
        }

        $norm1 = sqrt($norm1);
        $norm2 = sqrt($norm2);

        if ($norm1 == 0 || $norm2 == 0) {
            return 0.0;
        }

        return $dotProduct / ($norm1 * $norm2);
    }

    public function showPinVerification()
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }
        return view('kiosk.pin-verification');
    }

    public function verifyPin(Request $request)
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }

        $request->validate([
            'pin' => 'required|digits:4'
        ]);

        // Get the user we matched with face recognition
        $user = User::where('id', session('kiosk_user_id'))
            ->where('created_by', session('kiosk_company_id'))
            ->where('kiosk_code', $request->pin)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function showClock()
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }
        return view('kiosk.clock');
    }

    public function getStatus(Request $request)
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $lastCheckin = Checkin::where('user_id', $request->user_id)
            ->whereNull('checked_out_at')
            ->latest()
            ->first();

        return response()->json([
            'is_clocked_in' => $lastCheckin ? true : false
        ]);
    }

    public function clockInOut(Request $request)
    {
        if ($redirect = $this->checkCompanyVerification()) {
            return $redirect;
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'note' => 'nullable|string'
        ]);

        $user = User::where('id', $request->user_id)
                    ->where('created_by', session('kiosk_company_id'))
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user'
            ]);
        }

        // Check for any active clock-in record
        $activeCheckin = Checkin::where('user_id', $user->id)
            ->whereNull('checked_out_at')
            ->latest()
            ->first();

        if ($activeCheckin) {
            // Clock out - Update existing record
            $now = Carbon::now();
            $activeCheckin->update([
                'checked_out_at' => $now,
                'note' => $request->note ?: $activeCheckin->note
            ]);

            // Clear session
            session()->flush();

            return response()->json([
                'success' => true,
                'message' => 'Successfully clocked out',
                'action' => 'clock_out',
                'time' => $now->format('H:i:s')
            ]);
        }

        // Clock in - Create new record
        $now = Carbon::now();
        Checkin::create([
            'user_id' => $user->id,
            'checked_in_at' => $now,
            'checked_out_at' => null,
            'note' => $request->note ?? ''
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully clocked in',
            'action' => 'clock_in',
            'time' => $now->format('H:i:s')
        ]);
    }

    public function goBack()
    {
        session()->forget('kiosk_company_id');
        return redirect()->route('kiosk.start');
    }

    public function getHistory(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Get today's clock records
        $history = Checkin::where('user_id', $request->user_id)
            ->whereDate('checked_in_at', Carbon::today())
            ->orderBy('checked_in_at', 'desc')
            ->get()
            ->map(function($record) {
                return [
                    'checked_in_at' => $record->checked_in_at ? Carbon::parse($record->checked_in_at)->format('Y-m-d\TH:i:s') : null,
                    'checked_out_at' => $record->checked_out_at ? Carbon::parse($record->checked_out_at)->format('Y-m-d\TH:i:s') : null,
                    'note' => $record->note
                ];
            });

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }
} 