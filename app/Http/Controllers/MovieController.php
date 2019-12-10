<?php 

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use TSF\Neo4jClient\Facades\Neo4jClient;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $records = [];
        $query = "";
        if ($request->query('search')) {
            $search = $request->query('search');
            $query = "MATCH (m:Movie) WHERE toLower(m.title) CONTAINS toLower('${search}') return id(m),m";
        }
        else {
            $query = "MATCH (m:Movie) return id(m),m ORDER BY m.released DESC";
        }

        $result = Neo4jClient::run($query);
        $records = $result->getRecords();
        return view('movie.index', compact('records'));
    }

    public function show($id)
    {
        // Get all movie which id is equal our selected id, then get all person has relationship
        // with movie returned as m, then return any kind of relationship between them, as r,
        //collect will transfer result to array
        $query = "MATCH (m:Movie) WHERE id(m) = ${id} OPTIONAL MATCH (m)<-[r]-(a:Person) RETURN m, type(r), collect({rel: r, actor: a}) as plays";
        $result = Neo4jClient::run($query);

        $output = [
            'title' => $result->getRecord()->value('m')->title,
            'cast' => []
        ];

        //loop through result and return plays, since we return plays in Cypher query
        foreach($result->getRecords() as $record) {
            foreach($record->value('plays') as $play) {
                $actor = $play['actor']->value('name');
                $job = $record->value('type(r)');
                $output['cast'][] = [
                    'job' => $job,
                    'name' => $actor,
                    //check if role is exist when we get, role is exist if role of actor is "ACTED_IN"
                    'role' => array_key_exists('roles', $play['rel']->values()) ? implode($play['rel']->value('roles'), ",") : null
                ];
            }
        }

        $cast = $output['cast'];
        $title = $output['title'];
        return view('movie.show', compact('title','cast'));
    }

    public function create()
    {
        return view('movie.create');
    }

    public function store(Request $request)
    {
        $movie = Movie::NODE_NAME;
        $title = $request->input('title');
        $released = $request->input('released');
        $tagline = $request->input('tagline');
        $query = "CREATE (m:${movie} {title: '${title}' , released: '${released}', tagline: '${tagline}'})";
        Neo4jClient::run($query);
        return redirect()->action('MovieController@index');
    }

    public function edit($id)
    {
        $query = "MATCH (m:Movie) WHERE id(m)=$id return m";
        $result = Neo4jClient::run($query);
        $movie = $result->getRecord();
        return view('movie.edit', compact('id','movie'));
    }

    public function update($id, Request $request)
    {
        $movie = Movie::NODE_NAME;
        $title = $request->input('title');
        $released = $request->input('released');
        $tagline = $request->input('tagline');
        $query = "MATCH (m:${movie})  WHERE id(m) = $id SET m.title='$title', m.released=$released, m.tagline='$tagline'";
        Neo4jClient::run($query);
        return redirect()->action('MovieController@index');
    }

    public function destroy($id) 
    {
        $movie = Movie::NODE_NAME;
        $query = "MATCH (m:${movie}) WHERE id(m) = $id DETACH DELETE m"; //remove node and relationship of m
        Neo4jClient::run($query);
        return redirect()->action('MovieController@index');
    }
}