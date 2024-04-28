<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use App\Models\User;
use App\Notifications\NewJobListing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $url = 'https://mrge-group-gmbh.jobs.personio.de/xml';
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($handle);
        curl_close($handle);

        $data = new \SimpleXMLElement($response);
        $json = json_encode($data);
        $array = json_decode($json,TRUE);

        $jobs = JobListing::with('employer')
        ->where('is_publish', 1)
        ->latest()->simplePaginate(5);

        return view('jobs.index', [
            'jobs' => $jobs,
            'external_job' => $array
        ]);
    }

    public function post_jobs()
    {
        $post_jobs = JobListing::with('employer')
        ->where('is_publish', 0)
        ->latest()->simplePaginate(5);

        return view('jobs.post-jobs', [
            'post_jobs' => $post_jobs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'min:3'],
            'salary' => ['required'],
            'description' => ['required']
        ]);

        $job_listing = JobListing::create([
            'title' => $request['title'],
            'salary' =>  $request['salary'],
            'description' => $request['description'],
            'employer_id' => Auth::user()->employer->id
        ]);

        $is_first_job_post = JobListing::where('employer_id', $job_listing->employer->id)->get();

        if(count($is_first_job_post) == 1){
            $user = User::where('role', 'moderator')->first();
            $data['link'] = url('/jobs/'.$job_listing->id);
            $user->notify(new NewJobListing($data));
        }

        return redirect('/post-jobs');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobListing $job)
    {
        return view('jobs.job', [
            'job' => $job
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function publish(JobListing $job)
    {
        $job->is_publish = 1;
        $job->save();

        return redirect('/jobs');
    }
}
