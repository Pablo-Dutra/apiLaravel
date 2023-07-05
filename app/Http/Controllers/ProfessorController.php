<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProfessorRequest;
use App\Http\Resources\ProfessorResource;
use App\Models\Professor;
use Illuminate\Http\Request;
class ProfessorController extends Controller
{
    // LISTAR TODOS PROFESSORES
    public function index(){
        return ProfessorResource::collection(Professor::all());
    }

    // BUSCAR UM PROFESSOR
    public function show($id){
        $professor = Professor::find($id);
        if($professor){
            return ProfessorResource::make($professor);
        }else{
            return response()->json(null,404);
        }
    }

    // CRIAR UM PROFESSOR
    public function store(StoreProfessorRequest $request){
        $professor = Professor::create($request->validated());
        return ProfessorResource::make($professor);
    }

    // EDITAR UM PROFESSOR
    public function update(StoreProfessorRequest $request, string $id){
        $professor = Professor::find($id);
        if($professor){
            $professor->update($request->validated());
            return ProfessorResource::make($professor);
        }else{
            return response()->json(null,404);
        }
    }

    // DELETAR UM PROFESSOR
    public function destroy(string $id){
        $professor = Professor::find($id);
        if($professor){
            $professor->delete();
            return response()->noContent();
        }else{
            return response()->json(null,404);
        }
    }
}
