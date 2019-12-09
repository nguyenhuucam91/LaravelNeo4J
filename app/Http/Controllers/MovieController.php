<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TSF\Neo4jClient\Facades\Neo4jClient;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $records = [];
        if ($request->query('search')) {
            $search = $request->query('search');
            $result = Neo4jClient::run("MATCH (m:Movie) WHERE toLower(m.title) CONTAINS toLower('${search}') return m");
            $records = $result->getRecords();
        }
        return view('movie.index', compact('records'));
    }

    public function view($title)
    {
        // Get all movie which title is equal our selected title, then get all person has relationship
        // with movie returned as m, then return any kind of relationship between them, as r,
        //collect will transfer result to array
        $result = Neo4jClient::run("MATCH (m:Movie) WHERE m.title = '${title}' 
        OPTIONAL MATCH (m)<-[r]-(a:Person) RETURN m, type(r), collect({rel: r, actor: a}) as plays");

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
        return view('movie.show', compact('title', 'cast'));
    }
}