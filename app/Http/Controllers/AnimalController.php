<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
class AnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $request->url;
        $queryParams = $request->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";
        if (Cache::has($fullUrl)) {
            return Cache::get($fullUrl);
        }

        //Setting 
        $limit = $request->limit ?? 10;
        $query = Animal::query();
        //filters
        if (isset($request->filters)) {
            $filters = explode(',' , $request->filters);
            foreach($filters as $key => $filter) {
                list($key, $value) = explode(':' , $filter);
                $query->where($key, 'like', '%$value%');
            }
        }
        //sorts
        if (isset($request->sorts)) {
            $sorts = explode(',', $request->sorts);
            foreach($sorts as $key => $sort) {
                list($key, $value) = explode(':' , $sort);
                if ($sort == 'asc' || $sort == 'desc') {
                    $query->orderBy($key, $value);
                }
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $animals = Animal::paginate($limit)->appends($request->query());
        return Cache::remember($fullUrl, 60, function() use ($animals) {
            return response($animals, Response::HTTP_OK);
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'type_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'area' => 'nullable|string|max:255',
            'fix' => 'required|boolean',
            'description' => 'nullable',
            'personality' => 'nullable'
        ]);
        $animal = Animal::create($request->all());
        $animal = $animal->refresh();
        return response($animal, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function show(Animal $animal)
    {
        return response($animal, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function edit(Animal $animal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Animal $animal)
    {
        
        $this->validate($request,[
            'type_id' => 'nullable|integer',
            'name' => 'string|max:255',
            'birthday' => 'nullable|date',
            'area' => 'nullable|string|max:255',
            'fix' => 'boolean',
            'description' => 'nullable|string',
            'personality' => 'nullable|string'
        ]);
        
        $animal->update($request->all());
        return response(null, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Animal  $animal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Animal $animal)
    {
        $animal->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
