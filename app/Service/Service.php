<?php 
namespace App\Service;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;

class Service
{
    /**
     * Method to search a user logged
     * @param  \Illuminate\Http\Request  $request
     * @return object $user
     */
    public function getAuthUser(Request $request)
    {
        try
        {
            if (isset($_SERVER['HTTP_TOKEN']))
            {
                $user = JWTAuth::toUser($_SERVER['HTTP_TOKEN']);
            }
            else 
            {
                $user = JWTAuth::toUser($request->token);
            }
            
            return $user;            
        }
        catch (\Exception $e)
        {
            throw new Exception("Error to validate a user", 0, $e);
        }
    }

    /**
     * Validate if user logged is a student
     * @param  \Illuminate\Http\Request  $request
     */
    public function getStudentLogged(Request $request)
    {
        try
        {
            $user_logged = JWTAuth::toUser($request->token);

            if(!$user_logged)
            {
                throw new Exception("Not logged", 0, $e);
            }

            $student = $user_logged->students()->first();

            if (!$student)
            {
                throw new Exception("Student not authenticate", 0, $e);
            }

            return true;

        }
        catch (\Exception $e)
        {
            throw new Exception("Error to validate a student", 0, $e);
        }
    }

    /**
     * Validate if user logged is a teacher
     * @param  \Illuminate\Http\Request  $request
     */
    public function getTeacherLogged(Request $request)
    {
        try
        {
            $user_logged = JWTAuth::toUser($request->token);

            if (!$user_logged)
            {
                throw new \Exception("Not logged", 0, $e);
            }
            
            $teachers = $user_logged->teachers()->first();
            
            if (!$teachers)
            {
                throw new \Exception("Teachers not authenticate", 0, $e);
            }

            return true;

        } catch (\Exception $e) {
            throw new \Exception("Error to validate a teacher", 0, $e);
        }
    }
}
?>