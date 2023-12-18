<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    public function registro(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'name' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required',
        ]);

        // Crear un nuevo usuario
        $user = new User();
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;

        // Guardar el usuario
        $user->save();

        return redirect('/usuarios');
    }

    public function login(Request $request)
    {
        // Obtén la ID del usuario donde el correo electrónico sea igual a $request->email
        $userId = User::where('email', $request->email)->value('id');

        $credentials = [
            "email" => $request->email,
            "password" => $request->password
        ];

        $remember = ($request->has('remember') ? true : false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Obtén el usuario autenticado
            $user = Auth::user();

            // Verifica si es el primer inicio de sesión
            if ($user->isFirstLogin()) {
                // Si es el primer inicio de sesión, redirige a la vista para cambiar la contraseña
                return view('firstLogin', compact('userId'));
            }

            // Redirecciona según el rol
            switch ($user->role) {
                case 'admin':
                case 'planeacion':
                    return redirect('/');
                    break;
                case 'linea':
                case 'esmalte':
                    return redirect('/ordenes-produccion/create');
                    break;
                case 'almacen':
                    return redirect('/ordenes-produccion');
                    break;
                default:
                    return redirect('/');
                    break;
            }
        } else {
            return redirect('/login');
        }
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showLoginForm()
    {
        // Verificar si el usuario ya está autenticado
        if (Auth::check()) {
            // Obtén el usuario autenticado
            $user = Auth::user();

            // Redirigir según el rol
            switch ($user->role) {
                case 'admin':
                case 'planeacion':
                    return redirect('/');
                    break;
                case 'linea':
                case 'esmalte':
                    return redirect('/ordenes-produccion/create');
                    break;
                case 'almacen':
                    return redirect('/ordenes-produccion');
                    break;
                default:
                    return redirect('/');
                    break;
            }
        }

        return view('login');
    }

    public function showFirstLoginForm()
    {
        // Verificar si el usuario ya está autenticado
        if (Auth::check()) {
            // Obtén el usuario autenticado
            $user = Auth::user();

            // Redirigir según el rol
            switch ($user->role) {
                case 'admin':
                case 'planeacion':
                    return redirect('/');
                    break;
                case 'linea':
                case 'esmalte':
                    return redirect('/ordenes-produccion/create');
                    break;
                case 'almacen':
                    return redirect('/ordenes-produccion');
                    break;
                default:
                    return redirect('/');
                    break;
            }
        }

        return view('firstLogin');
    }

    public function index()
    {
        // Obtener el usuario autenticado
        $currentUser = Auth::user();

        // Obtener todos los usuarios excepto el usuario autenticado
        $users = User::where('id', '!=', $currentUser->id)->get();

        // Pasar los usuarios filtrados a la vista
        return view('usuarios.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('usuarios.edit', compact('user'));
    }

    public function editPassword($id)
    {
        $user = User::find($id);

        return view('usuarios.editPassword', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'name' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required',
        ]);

        $user->update($request->all());

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }  

    public function updatePassword(Request $request, $id)
    {
        // Obtener el usuario
        $user = User::find($id);

        // Obtener el usuario autenticado
        $currentUser = Auth::user();

        // Hashear la nueva contraseña
        $hashedPassword = Hash::make($request->input('password'));

        // Actualizar la contraseña hasheada en el modelo del usuario
        $user->password = $hashedPassword;

        // Actualizar el campo password_changed_at
        $user->password_changed_at = null;

        // Guardar el modelo actualizado en la base de datos
        $user->save();

        // Redirecciona según el rol
        switch ($currentUser->role) {
            case 'admin':
                return redirect()->route('usuarios.index')->with('success', 'Contraseña actualizada exitosamente.');
                break;
            case 'planeacion':
                return redirect('/');
                break;
            case 'linea':
            case 'esmalte':
                return redirect('/ordenes-produccion/create');
                break;
            case 'almacen':
                return redirect('/ordenes-produccion');
                break;
            default:
                return redirect('/');
                break;
        }
    }

    public function updatePasswordFirstLogin(Request $request, $id)
    {
        // Obtener el usuario
        $user = User::find($id);

        // Obtener el usuario autenticado
        $currentUser = Auth::user();

        // Hashear la nueva contraseña
        $hashedPassword = Hash::make($request->input('password'));

        // Actualizar la contraseña hasheada en el modelo del usuario
        $user->password = $hashedPassword;

        // Actualizar el campo password_changed_at
        $user->password_changed_at = now();

        // Guardar el modelo actualizado en la base de datos
        $user->save();

        // Redirecciona según el rol
        switch ($currentUser->role) {
            case 'admin':
                return redirect()->route('usuarios.index')->with('success', 'Contraseña actualizada exitosamente.');
                break;
            case 'planeacion':
                return redirect('/');
                break;
            case 'linea':
            case 'esmalte':
                return redirect('/ordenes-produccion/create');
                break;
            case 'almacen':
                return redirect('/ordenes-produccion');
                break;
            default:
                return redirect('/');
                break;
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        try {
            // Elimina el usuario
            $user->delete();

            return redirect()->route('usuarios')->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
