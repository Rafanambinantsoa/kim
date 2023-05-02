<?php

namespace App\Http\Controllers;

use App\Events\OurExampleEvent;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

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

    //focntion permettant de partager les donnee qui se repetent dans le 3 fonction (profile , profileFollowers , profileFollowings)
    private function getSharedData($user){
        //verification s'il a dejà suivi la personne en question
        $currentFollowings = 0 ; 
        $blogsCount = $user->posts()->latest()->get()->count();
        if(auth()->check()){
            $currentFollowings = Follow::where([
                ['user_id' , '=' , auth()->user()->id],
                ['followeduser' , '=' , $user->id]
            ])->count();
        }

        View::share('sharedData' ,[
            'username' => $user->username ,
            'avatar' => $user->avatar ,
            'blogsCount' => $blogsCount ,
            'followersCount' => $user->followers()->count() ,
            'followingsCount' => $user->followingsThisUser()->count() ,
            'currentFollowings' => $currentFollowings
        ] );
    }

    public function profile(User $user){
        $blogs = $user->posts()->latest()->get();
        $this->getSharedData($user);

        
        // dd($currentFollowings);
        return view('profile-posts' , [
            'posts' => $blogs,
        ]);
    }

    //Amélioration en js
    public function profileRaw(User $user){
        $blogs = $user->posts()->latest()->get();

        return response()->json([
            'theHTML' => view('profile-posts-only' , [
                'posts' => $blogs,
            ])->render() ,
            'docTitle' => $user->username . "'s profile"
        ]);
    }

    public function profileFollowers(User $user){
        $followers = $user->followers()->latest()->get();
        $this->getSharedData($user);

        
        // dd($currentFollowings);
        return view('profile-followers' , [
            'followers' => $followers,
        ]);
    }
    //Amélioration js
    public function profileFollowersRaw(User $user){
        $followers = $user->followers()->latest()->get();

        return response()->json([
            'theHTML' => view('profile-followers-only' , [
                'followers' => $followers,
            ])->render() ,
            'docTitle' => $user->username . "'s followers"
        ]);
    }

    public function profileFollowings(User $user){
        $followings = $user->followingsThisUser()->latest()->get();
        $this->getSharedData($user);

        
        // dd($currentFollowings);
        return view('profile-followings' , [
            'followings' => $followings,
        ]);
    }

    public function profileFollowingsRaw(User $user){
        $followings = $user->followingsThisUser()->latest()->get();

        return response()->json([
            'theHTML' => view('profile-followings-only' , [
                'followings' => $followings,
            ])->render() ,
            'docTitle' => $user->username . "'s followings"
        ]);
    }

    public function logout(){
        event(new OurExampleEvent([
            'username' => auth()->user()->username , 
            'action' => 'logout' 
        ]));
        Auth::logout();
        return redirect('/')->with('success' , 'You are logged out');
    }

    public function showCorrectHomePage(){
        if (auth()->check()){
            // return view('homePage-feed' , ['posts' => auth()->user()->postFeed()->latest()->get()]);
            // Pour le pagination automatique en laravel au lieu de get on utilise paginate avec le parametre du nombre de post par page
            return view('homePage-feed' , ['posts' => auth()->user()->postFeed()->latest()->paginate(4)]);

        }else{
            $postCount = Cache::remember('postCount', 20, function () {
                sleep(5);
                return Post::count();
            });
            return view('homePage' , ['postCount' => $postCount ]);
        }
    }

    public function loginApi(Request $request){
        $incomingsFields = $request->validate([
            'username' => 'required' ,
            'password' => 'required'
        ]);

        if(Auth::attempt($incomingsFields)){
            $user = User::where('username' , $incomingsFields['username'])->first();
            $token = $user->createToken('OurAppToken')->plainTextToken;
            return response()->json([
                'token' => $token
            ]);
        }else{
            return response()->json([
                'error' => 'Wrong credentials'
            ]);
        }
    }
    

    public function login(Request $request)
    {
        $data = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required']);

        if (Auth::attempt(['username' => $data['loginusername'], 'password' =>$data['loginpassword']])) {
            event(new OurExampleEvent([
                'username' => auth()->user()->username , 
                'action' => 'login' 
            ]));
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
