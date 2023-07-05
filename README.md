Aplicativo criado para a disciplina Serviços Web do curso de pós graduação em Desenvolvimento de Sistemas Web do Instituto Federal do Sudeste de Minas Gerais.

Consiste em uma API PHP conectando em um banco de dados MySQL e retornando os resultados em REST

Foi construido usando o Framewok Laravel para PHP https:laravel.com


Passo a passo para a criação da API:

1. Criar uma aplicação Laravel:
composer create-project –-prefer-dist laravel/laravel apiPablo

2. Configurar o banco de dados no .env

3. Crie a migration para criação da tabela de professores.
php artisan make:migration create_professores_table

4. Altere o método up() da migration conforme mostrado abaixo:
Arquivo: database\migrations\2014_10_12_000000_create_users_table.php
public function up(): void {
    Schema::create('professores', function (Blueprint $table) {
        $table->id();
        $table->string('nome',200);
        $table->string('titulacao',200);
        $table->timestamps();
    });
}

5. a) Execute as migrations.
php artisan migrate

5. b) Se precisar, para desfazer as alterações feitas pelas migrações, use:
php artisan migrate:reset

6. Crie o modelo Professor e a factory que utilizaremos para popular a tabela com dados para testes.
php artisan make:model Professor -f

7. Como o padrão utilizado pelo laravel não identificará a tabela professores, defina a variável table no modelo.
arquivo: app\Models\Professor.php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Professor extends Model{
    protected $fillable = ['nome','titulacao'];
    protected $table='professores';
    use HasFactory;
}

8. Altere a classe ProfessorFactory (App-database-factory) conforme mostrado na abaixo:
Arquivo: - database\factories\ProfessorFactory.php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ProfessorFactory extends Factory
{
    public function definition(): array
    {
        $titulos = ['Graducação','Especialização','Mestrado','Doutorado'];
        return [
            'nome'=>fake()->name,
            'titulacao'=>$titulos[rand(0,3)]
        ];
    }
}

9. Altere a classe DatabaseSeeder (App-database-seeders)
Arquivo: database\seeders\DatabaseSeeder.php

namespace Database\Seeders;
use App\Models\Professor;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        Professor::factory(100)->create();
    }
}

10. Execute o seed para popular a tabela:
php artisan migrate --seed

11. Para finalizar esta parte estrutural do projeto, crie o controller para a classe professor, com os métodos de Adicionar, Listar, Excluir e Buscar.
php artisan make:controller ProfessorController
Arquivo: app\Http\Controllers\ProfessorController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfessorRequest;
use App\Http\Resources\ProfessorResource;
use App\Models\Professor;
use Illuminate\Http\Request;
class ProfessorController extends Controller
{
     LISTAR TODOS PROFESSORES
    public function index(){
        return ProfessorResource::collection(Professor::all());
    }

     BUSCAR UM PROFESSOR
    public function show($id){
        $professor = Professor::find($id);
        if($professor){
            return ProfessorResource::make($professor);
        }else{
            return response()->json(null,404);
        }
    }

     CRIAR UM PROFESSOR
    public function store(StoreProfessorRequest $request){
        $professor = Professor::create($request->validated());
        return ProfessorResource::make($professor);
    }

     EDITAR UM PROFESSOR
    public function update(StoreProfessorRequest $request, string $id){
        $professor = Professor::find($id);
        if($professor){
            $professor->update($request->validated());
            return ProfessorResource::make($professor);
        }else{
            return response()->json(null,404);
        }
    }

     DELETAR UM PROFESSOR
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

12. Definindo as rotas:
Arquivo: routes\api.php

use App\Http\Controllers\ProfessorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/professores',ProfessorController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Você pode visualizar as rotas que foram criadas com o comando:
php artisan route:list --path=api

13. Preparar o ProfessorResource e o StoreProfessorRequest

php artisan make:resource ProfessorResource
Arquivo: app\Http\Resources\ProfessorResource.php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class ProfessorResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'titulacao' => $this->titulacao
        ];
    }
}

php artisan make:request StoreProfessorRequest
Arquivo: app\Http\Requests\StoreProfessorRequest.php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreProfessorRequest extends FormRequest {
    public function authorize(): bool {
         AUTORIZAÇÃO PARA VERIFICAR SE TEM PERMISSÃO DE INSERIR
        return true;
    }
    public function rules(): array {
        return [
            'nome'=> ['required','max:200'],
            'titulacao'=>['required','max:200']
        ];
    }
}

14. Para testar, inicie o servidor:
php artisan serve

Acesse: http:127.0.0.1:8000/api/professores/

