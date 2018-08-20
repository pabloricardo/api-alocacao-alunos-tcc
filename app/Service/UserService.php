<?php
namespace App\Service;

use Illuminate\Http\Request;
use App\Student;
use App\Teatcher;
use App\User;

class UserService extends Service
{
    private $user;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->student  = new Student();
        $this->teatcher = new Teatcher();
        $this->user     = new User();
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
                'teacthcerId' => $request->get('teacthcerId')
            ]);

            return $returnStudent;
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a user", 0, $e);
        }
    }

    /**
     * Create a type teatcher or ADM of user
     * @param  \Illuminate\Http\Request  $request
     * @param int $userID
     * @return object $user or false
     */
    public function createTeatcherOrAdm(Request $request, $userID)
    {
        try
        {
            $returnTeatcherADM = $this->teatcher->create([
                'type' => $request->get('type'),
                'studentLimit' => $request->get('studentLimit'),
                'userId' => $userID
            ]);

            return $returnTeatcherADM;
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a user", 0, $e);
        }
    }
}
?>