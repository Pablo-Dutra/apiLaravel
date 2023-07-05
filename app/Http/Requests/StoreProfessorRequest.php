<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreProfessorRequest extends FormRequest {
    public function authorize(): bool {
        // AUTORIZAÇÃO PARA VERIFICAR SE TEM PERMISSÃO DE INSERIR
        return true;
    }
    public function rules(): array {
        return [
            'nome'=> ['required','max:200'],
            'titulacao'=>['required','max:200']
        ];
    }
}
