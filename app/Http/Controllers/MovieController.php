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
            $result = Neo4jClient::run("MATCH (m:Movie) WHERE toLower(m.title) CONTAINS '${search}' return m");
            $records = $result->getRecords();
        }
        return view('movie.index', compact('records'));
    }

    public function view($title)
    {
        $result = Neo4jClient::run("MATCH (n:Movie)<-[rel]-(p:Person) WHERE n.title= '${title}' return type(rel),p.name");
        $crew = $result->getRecords();
        $crews = [];
        foreach($crew as $actor) {
            if ($actor->value('type(rel)') === 'ACTED_IN') {
                $actorName = $actor->value('p.name');
                $actedInCrews = Neo4jClient::run("MATCH (p:Person)-[rel:ACTED_IN]->(m:Movie) WHERE m.title = '${title}' AND p.name='${actorName}' 
                return p.name, type(rel), rel.roles");
                $crews[] = $actedInCrews->getRecord(); //get first record matched only, if found, push to array
            }
            else {
                $crews[] = $actor;
            }
        }
        return view('movie.show', compact('title', 'crews'));
    }
}