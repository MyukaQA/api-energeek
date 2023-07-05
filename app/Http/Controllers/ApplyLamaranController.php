<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Skill;
use App\Models\SkillSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplyLamaranController extends Controller
{
    public function apply(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'job_id' => 'required|exists:jobs,id',
                'email' => 'required|email|unique:candidates,email',
                'phone' => 'required|numeric|unique:candidates,phone',
                'year' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $skillsValidator = Validator::make($request->all(), [
                'skills' => 'required',
                'skills.*' => 'string|exists:skills,name',
            ]);

            if ($skillsValidator->fails()) {
                return response()->json(['errors' => $skillsValidator->errors()], 400);
            }

            $data = $request->only(['name', 'job_id', 'email', 'phone', 'year']);
            $skills = $request->input('skills');

            $candidate = Candidate::create($data);

            foreach ($skills as $skillName) {
                $skill = Skill::where('name', $skillName)->first();
                $skillSet = SkillSet::create([
                    'candidate_id' => $candidate->id,
                    'skill_id' => $skill->id,
                ]);
            }
            DB::commit();

            return response()->json(['message' => 'Kandidat Berhasil di buat', 'data' => $candidate], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Ada kesalahan', 'error' => $e->getMessage()]);
        }
    }
}
