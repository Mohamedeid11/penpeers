<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\EditSettings;
use App\Models\Setting;
use App\Models\TracingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(Request $request){
        $this->authorize('viewAny', Setting::class);
        $must_exist = config('settings');
        foreach ($must_exist as $item){
            Setting::firstOrCreate($item[0], $item[1]);
        }
        $setting = new Setting;
        return view('admin.settings.index', compact('setting'));
    }
    public function update(EditSettings $request, $setting_id){
        $setting = Setting::find($setting_id);
            if($setting->input_type != 'file' && $setting->input_type != 'checkbox') {
                $setting->update(['value' => $request->input($setting->name)]);
            }
            else if ($setting->input_type == 'checkbox'){
                $setting->update(['value' => $request->filled($setting->name) ? '1' : null]);
            }
            else if ($setting->input_type == 'file'){
                if($request->hasFile($setting->name)) {
                    Storage::disk('public')->delete($setting->value);
                    $path = $request->file($setting->name)->store('uploads/settings', ['disk' => 'public']);
                    $setting->update(['value' => $path]);
                }
            }
        $request->session()->flash('success',  __('admin.success_edit', ['thing'=>__('global.settings')]) );
        return back();
//        return [
//         'status' => 1,
//            'message'=> __('admin.success_edit', ['thing'=>__('global.settings')])
//        ];
    }

    public function tracingEmail()
    {
        $tracing_emails = TracingEmail::with('book' , 'sender' , 'receiver')->get();

        return view('admin.tracing_emails.index', compact('tracing_emails'));
    }

}
