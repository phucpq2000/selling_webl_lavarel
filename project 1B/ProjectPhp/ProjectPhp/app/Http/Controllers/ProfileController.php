<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile =  DB::table('profiles')->where('user_id',$id)->first();

		return View('pages.profiles.show',['profile'=>$profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profile =  DB::table('profiles')->where('user_id',$id)->first();
		return View('pages.profiles.edit',['profile'=>$profile]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'avatar' => 'nullable|mimes:jpg,jpeg,png,xlx,xls,pdf|max:2048',
			'birthday'=>'nullable|date',
            'full_name' =>'required',
            'address' =>'required',
        ]);

        if ($validate) {
            $profile = Profile::find($id);
			$profile->full_name = $request->input('full_name');
			$profile->address = $request->input('address');
			$profile->birthday = $request->input('birthday');
			// $affected = DB::table('profiles')
			// 	->where('id', $id)
			// 	->update(['full_name' =>  $profile->full_name,
			// 				'address' =>  $profile->address,
			// 				'birthday' =>  $profile->birthday
			// 		]);

            if ($request->file()) {
                $fileName = $request->file('avatar')->getClientOriginalName();
                $filePath = $request->file('avatar')->storeAs('uploads', $fileName, 'public');
                //tham s??? th??? 3 l?? ch??? l??u tr??n disk 'public', tham s??? th??? 1:  l??u trong th?? m???c 'uploads' c???a disk 'public'
                $profile->avatar = '/storage/' . $filePath;
            // $filepath='uploads/'+$fileName --> $profile->avatar = 'storage/uploads/tenfile --> ???????ng d???n h??nh trong th?? m???c public
            $profile->save();

            return back()//tr??? v??? trang tr?????c ????
                    ->with('success', 'C???p nh???t h??nh ???nh th??nh c??ng!!!')//l??u th??ng b??o k??m theo ????? hi???n th??? tr??n view
                    ->with('file', $fileName);
            } else {
               
                return back()//tr??? v??? trang tr?????c ????
                        ->with('error', 'C???p nh???t b??? l???i')//l??u th??ng b??o k??m theo ????? hi???n th??? tr??n view
                        ->with('file', $fileName);
            }
 

        }

        // return redirect()->route('profiles.show', [$id]);
        return back()->with($validate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profile =  DB::table('profiles')->where('user_id',$id)->delete();
        return redirect()->route('profiles.show', [$id]);
    }
}
