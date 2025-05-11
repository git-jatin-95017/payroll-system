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
        session(['kiosk_company_id' => $company->user_id]);

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

        // Log incoming request details
        \Log::info('Face verification request received:', [
            'feature_count' => count($request->face_features),
            'company_id' => session('kiosk_company_id'),
            'timestamp' => now()->toDateTimeString(),
            'feature_sample' => array_slice($request->face_features, 0, 3) // Log first 3 features for debugging
        ]);

        // Get all employees created by the current company with face data
        $employees = User::where('created_by', session('kiosk_company_id'))
            ->whereHas('employeeProfile', function($query) {
                $query->whereNotNull('face_data');
            })
            ->with('employeeProfile')
            ->get();

        \Log::info('Found employees with face data:', [
            'count' => $employees->count(),
            'company_id' => session('kiosk_company_id')
        ]);

        if ($employees->isEmpty()) {
            \Log::warning('No employees found with face data', [
                'company_id' => session('kiosk_company_id')
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No registered employees found with face data'
            ]);
        }

        // Find the best match by comparing face features
        $bestMatch = null;
        $highestSimilarity = 0;
        $threshold = 0.55; // Lowered threshold to account for variations in capture conditions
        $matchDetails = [];

        foreach ($employees as $employee) {
            try {
                $storedFaces = json_decode($employee->employeeProfile->face_data, true);
                if (!is_array($storedFaces)) {
                    \Log::warning("Invalid face data format for employee", [
                        'employee_id' => $employee->id,
                        'face_data_type' => gettype($employee->employeeProfile->face_data)
                    ]);
                    continue;
                }

                \Log::info("Processing employee faces", [
                    'employee_id' => $employee->id,
                    'stored_faces_count' => count($storedFaces)
                ]);

                foreach ($storedFaces as $index => $storedFace) {
                    // Skip if stored face data is invalid
                    if (!isset($storedFace['features']) || !is_array($storedFace['features'])) {
                        \Log::warning("Invalid features format for employee", [
                            'employee_id' => $employee->id,
                            'face_index' => $index,
                            'features_type' => isset($storedFace['features']) ? gettype($storedFace['features']) : 'not set'
                        ]);
                        continue;
                    }

                    // Only compare if feature counts match
                    if (count($request->face_features) !== count($storedFace['features'])) {
                        \Log::warning("Feature count mismatch", [
                            'employee_id' => $employee->id,
                            'face_index' => $index,
                            'input_features_count' => count($request->face_features),
                            'stored_features_count' => count($storedFace['features']),
                            'input_features_sample' => array_slice($request->face_features, 0, 3),
                            'stored_features_sample' => array_slice($storedFace['features'], 0, 3)
                        ]);
                        continue;
                    }

                    // Calculate similarity first
                    $similarity = $this->calculateCosineSimilarity(
                        $request->face_features,
                        $storedFace['features']
                    );

                    // Log each comparison
                    \Log::info("Face comparison result", [
                        'employee_id' => $employee->id,
                        'face_index' => $index,
                        'similarity_score' => $similarity,
                        'threshold' => $threshold,
                        'input_features_count' => count($request->face_features),
                        'stored_features_count' => count($storedFace['features'])
                    ]);

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
                        \Log::info("New best match found", [
                            'employee_id' => $employee->id,
                            'similarity' => $similarity,
                            'threshold' => $threshold
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error("Error processing face data for employee", [
                    'employee_id' => $employee->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                continue;
            }
        }

        // Log final match details
        \Log::info('Face verification complete', [
            'highest_similarity' => $highestSimilarity,
            'threshold' => $threshold,
            'best_match_id' => $bestMatch ? $bestMatch->id : null,
            'match_details' => $matchDetails,
            'total_comparisons' => count($matchDetails)
        ]);

        if (!$bestMatch || $highestSimilarity < $threshold) {
            \Log::warning('Face verification failed - no match above threshold', [
                'highest_similarity' => $highestSimilarity,
                'threshold' => $threshold,
                'best_match_id' => $bestMatch ? $bestMatch->id : null
            ]);
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

        // Prefer employee profile face_data image if available, else use the just-captured face
        $profileFaceData = null;
        if ($bestMatch->employeeProfile && $bestMatch->employeeProfile->face_data) {
            $storedFaces = json_decode($bestMatch->employeeProfile->face_data, true);
            if (is_array($storedFaces) && isset($storedFaces[0]['image'])) {
                $profileFaceData = $storedFaces[0]['image'];
            }
        }
        session(['kiosk_captured_face' => $profileFaceData ?: $request->face_data]);

        \Log::info('Face verification successful', [
            'employee_id' => $bestMatch->id,
            'similarity' => $highestSimilarity,
            'threshold' => $threshold
        ]);

        session()->forget('kiosk_captured_face');

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

            // Clear only kiosk_company_id from session
            session()->forget('kiosk_company_id');
            
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
        return redirect()->route('kiosk.login');
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

    public function showFaceConfirmation()
    {
        if (!session('kiosk_user_id')) {
            return redirect()->route('kiosk.face-recognition');
        }
        $user = \App\Models\User::with('employeeProfile')->find(session('kiosk_user_id'));
        return view('kiosk.face-confirmation', [
            'user' => $user
        ]);
    }

    public function handleFaceConfirmation(Request $request)
    {
        $request->validate(['confirm' => 'required|in:yes,no']);
        if ($request->confirm === 'yes') {
            return redirect()->route('kiosk.pin-verification');
        } else {
            session()->forget('kiosk_user_id');
            return redirect()->route('kiosk.face-recognition');
        }
    }
} 