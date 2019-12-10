<?php 

namespace App\Http\Controllers;

use App\GlobalConfig\Role;
use Illuminate\Http\Request;
use TSF\Neo4jClient\Facades\Neo4jClient;

class MovieActorController extends Controller
{
    public function index($id)
    {
        $query = "MATCH (m:Movie) WHERE id(m) = ${id} OPTIONAL MATCH (m)<-[r]-(a:Person) RETURN m, id(a), type(r), collect({rel: r, actor: a}) as plays";
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
                    'role' => array_key_exists('roles', $play['rel']->values()) ? implode($play['rel']->value('roles'), ';') : null,
                    'id' => $record->get('id(a)')
                ];
            }
        }

        $cast = $output['cast'];
        $title = $output['title'];
        $roles = Role::ROLES;
        return view('movie-actor.index', compact('cast', 'title', 'roles', 'id'));
    }

    public function update($actorId, Request $request)
    {
        $filmId = $request->input('film_id');
        $movie = $request->input('as_role');
        $movies = strlen($movie) > 0 ? explode(";",$movie) : [];
        $coalesceString = "";
        if (count($movies) > 0) {
            foreach($movies as $movie) {
                $coalesceString .= "+'$movie'";
            }
        }
        
        $query = "MATCH (m:Movie) WHERE id(m)=$filmId OPTIONAL MATCH (m)<-[r]-(p:Person) WHERE id(p) = $actorId 
        SET r.roles=coalesce([])$coalesceString RETURN m,r,type(r),p";
        Neo4jClient::run($query);
        return redirect()->action('MovieActorController@index', ['id' => $filmId]);
    }
}