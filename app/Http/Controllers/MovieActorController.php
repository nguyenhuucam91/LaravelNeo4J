<?php 

namespace App\Http\Controllers;

use App\GlobalConfig\Role;
use TSF\Neo4jClient\Facades\Neo4jClient;

class MovieActorController extends Controller
{
    public function index($id)
    {
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
                    'role' => array_key_exists('roles', $play['rel']->values()) ? $play['rel']->value('roles') : null
                ];
            }
        }

        $cast = $output['cast'];
        $title = $output['title'];
        $roles = Role::ROLES;
        return view('movie-actor.index', compact('cast', 'title', 'roles'));
    }
}