<?php
namespace App\Service;

use Illuminate\Http\Request;
use App\Student;
use App\Teacher;
use App\User;
use App\Usuario;
use App\OcupationArea;

class UserService extends Service
{
    private $user;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->student  = new Student();
        $this->teacher = new Teacher();
        $this->user     = new User();
        $this->usuario     = new Usuario();
        $this->ocupationArea     = new OcupationArea();
    }

    /**
     * Create a user \App\User
     * @param  \Illuminate\Http\Request  $request
     * @return object $user or false
     */
    public function createUser(Request $request)
    {
        try
        {
            $returnUser = $this->user->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'idRegistration' => $request->get('idRegistration'),
                'cpf' => $request->get('cpf')
            ]);
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a user", 0, $e);
        }

        return $returnUser;
    }

    /**
     * 
     */
    public function criarUsuario(Request $request) 
    {
        
        try 
        {
            $novoUsuario = $this->usuario->create([
                'nome' => $request->get('nome'),
                'matricula' => $request->get('matricula'),
                'email' => $request->get('email'),
                'disciplina' => $request->get('disciplina'),
                'quantidade_orientacoes' => $request->get('quantidade_orientacoes'),
                'status' => $request->get('status'),
                'descricao' => $request->get('descricao')
            ]);

            return $novoUsuario;
        }
        catch (Exeception $e)
        {
            throw new Exception("Error ao criar usuario", 0, $e);
        }
    }

    /**
     * 
     */
    public function alocarProfessorArea(Request $request, $professorId)
    {
        try 
        {
            $retornoAlocacaoProfessor = [];

            if(is_array($request->get('areas')))
            {
                
                foreach ($request->get('areas') as $key => $value) {
                    
                    $retornoAlocacaoProfessor[] = $this->ocupationArea->create([
                        'id_area' => $value['id'],
                        'matricula' => $professorId
                    ]);
                }
            }            

            return $retornoAlocacaoProfessor;
        }
        catch (Exeception $e)
        {
            throw new Exception("Error ao alocar professor na area", 0, $e);
        }        
    }

    /**
     * Create a type student of user
     * @param  \Illuminate\Http\Request  $request
     * @param int $userID
     * @return object $user or false
     */
    public function createStudent(Request $request, $userID)
    {
        try
        {
            $returnStudent = $this->student->create([
                'curse' => $request->get('curse'),
                'userId' => $userID,
                'teacherId' => $request->get('teacherId')
            ]);

            return $returnStudent;
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a user", 0, $e);
        }
    }

    /**
     * Create a type teacher or ADM of user
     * @param  \Illuminate\Http\Request  $request
     * @param int $userID
     * @return object $user or false
     */
    public function createTeacherOrAdm(Request $request, $userID)
    {
        try
        {
            $returnTeacherADM = $this->teacher->create([
                'type' => $request->get('type'),
                'studentLimit' => $request->get('studentLimit'),
                'userId' => $userID
            ]);

            return $returnTeacherADM;
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a user", 0, $e);
        }
    }

    /**
     * Get student by id
     * @param int $userID
     * @return bool or object $response
     */
    public function getStudent($studentID)
    {
        $student = $this->student->find($studentID);

        if($student)
        {
            $user = $student->users()->first();

            if(!$user)
            {
                return false;
            }

            $response = ['user' => $user, "student" => $student];

            return $response;
        }

        return false;
    }

    /**
     * Get student by id
     * @param int $userID
     * @return bool or object $response
     */
    public function getTeacher($teacherID)
    {
        $teacher = $this->usuario->find($teacherID);
        
        print_r($teacher); exit;
        /*if($teacher)
        {
            $user = $teacher->users()->first();
            
            if(!$user)
            {
                return false;
            }

            $response = ['user' => $user, "teacher" => $teacher];

            return $response;
        }*/

        return false;
    }
}
?>