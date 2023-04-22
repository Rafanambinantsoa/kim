<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:3000' 
        ]);
        // $request->file('avatar')->store('public/avatars/');
        //Redimennsion de l'image en 120 width et 120 height
        $imgData = Image::make($request->file('avatar'))->resize(120, 120)->encode('jpg');
        //nom de l'image
        $user = auth()->user();
        $imgName = $user->id.'-'.uniqid().'.jpg';
        //enregistrement d'image dans le dossier 
        Storage::put('public/avatars/'. $imgName.'.jpg', $imgData);

        $oldImg = $user->avatar;
        // dd($oldImg);
        //BD
        $user->avatar = $imgName;
        $user->save();

        //Effacer l'image préccedents qui n'est plus utiliser dans notre app
        if($oldImg != "/fallback-avatar.jpg"){
            //str bla replace le storage par public  , le storage c'est pour le chemin web , mais pour le dossier c'est public
            Storage::delete(str_replace('/storage/','/public/',  $oldImg));
        }

        return redirect()->back()->with('success' , 'Avatar update successfully');
    }

    public function showAvatarForm(){
        return view('avatar-form');
    }

    public function profile(User $user){
        $blogs = $user->posts()->latest()->get();
        $blogsCount = $user->posts()->latest()->get()->count();


        //verification s'il a dejà suivi la personne en question
        $currentFollowings = Follow::where([
            ['user_id' , '=' , auth()->user()->id],
            ['followeduser' , '=' , $user->id]
        ])->count();



        // dd($currentFollowings);
        return view('profile-posts' , [
            'username' => $user->username ,
            'avatar' => $user->avatar ,
            'blogs' => $blogs,
            'blogsCount' => $blogsCount , 
            'currentFollowings' => $currentFollowings , 
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect('/')->with('success', 'You are logged out');
    }

    public function showCorrectHomePage(){
        if (auth()->check()){
            return view('homePage-feed');
        }else{
            return view('homePage');
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required']);

        if (Auth::attempt(['username' => $data['loginusername'], 'password' =>$data['loginpassword']])) {
            return redirect("/")->with('success', 'You are logged in');
        }
        else{
            return redirect('/')->with('error', 'Wrong username or password');
        }
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'password' => ['required', 'min:8']
        ]);

        $data['password'] = bcrypt($data['password']);
        // dd($data);
        $user = User::create($data);
        //Auto login
        auth()->login($user);
        return redirect('/')->with('success' , 'Thanks for registering');
    }
}
