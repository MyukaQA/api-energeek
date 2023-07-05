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
            ], [
                'name.required' => 'Entry nama wajib diisi.',
                'job_id.required' => 'Entry job id wajib diisi.',
                'job_id.exists' => 'Id job ini tidak ditemukan.',
                'email.required' => 'Entry email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah dipakai.',
                'phone.required' => 'Entry telepon wajib diisi.',
                'phone.unique' => 'Telepon sudah dipakai.',
                'year.required' => 'Entry Tahun wajib diisi.',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $skillsValidator = Validator::make($request->all(), [
                'skills' => 'required',
                'skills.*' => 'string|exists:skills,name',
            ], [
                'skills.required' => 'Entry skills wajib diisi.',
                'skills.*.exists' => 'Nama skill tidak ditemukan.',
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

            return response()->json(['message' => 'Kandidat berhasil di buat', 'status' => 'OK'], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Ada kesalahan', 'error' => $e->getMessage()]);
        }
    }
}
