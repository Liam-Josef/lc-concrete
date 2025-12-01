<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AppController extends Controller
{
    public function edit()
    {
        $settings = AppSetting::current();
        return view('admin.utilities.app.view', compact('settings'));
    }

    public function update(Request $request)
    {
        $app = AppSetting::first() ?? new AppSetting();

        $validated = $request->validate([
            'app_name'               => ['required','string','max:191'],
            'company_url'            => ['nullable','string','max:191'],
            'company_phone'          => ['nullable','string','max:191'],
            'company_email'          => ['nullable','email','max:191'],

            // remove `image` so SVG/ICO are allowed
            'logo'                   => ['nullable','mimes:png,jpg,jpeg,webp,svg'],
            'favicon'                => ['nullable','mimes:png,jpg,jpeg,webp,svg,ico'],
            'home_background'        => ['nullable','mimes:png,jpg,jpeg,webp,svg'],
            'internal_background'    => ['nullable','mimes:png,jpg,jpeg,webp,svg'],

            'exec_director_name'     => ['nullable','string','max:191'],
            // MATCH your hidden input name from the signature pad
            'exec_director_signature_data' => ['nullable','string'], // data:image/png;base64,...

            'ga_measurement_id'      => ['nullable','string','max:32'],
            'gtm_container_id'       => ['nullable','string','max:32'],
            'analytics_scripts'      => ['nullable','array'],
            'analytics_scripts.*'    => ['nullable','string'],

            'accreditation_image'    => ['nullable','mimes:png,jpg,jpeg,webp,svg'],
            'accreditation_image_alt'=> ['nullable','string','max:191'],
        ]);

        // helper for file fields
        $saveFile = function (string $key) use ($request, $app) {
            if ($request->hasFile($key)) {
                if (!empty($app->$key)) {
                    Storage::disk('public')->delete($app->$key);
                }
                return $request->file($key)->store('app-images', 'public');
            }
            return $app->$key; // keep existing if no new upload
        };

        // uploads
        $app->logo                = $saveFile('logo');
        $app->favicon             = $saveFile('favicon');
        $app->home_background     = $saveFile('home_background');
        $app->internal_background = $saveFile('internal_background');
        $app->accreditation_image = $saveFile('accreditation_image');

        // basics
        $app->app_name            = $validated['app_name'];
        $app->company_url         = $validated['company_url'] ?? null;
        $app->company_phone       = $validated['company_phone'] ?? null;
        $app->company_email       = $validated['company_email'] ?? null;

        $app->exec_director_name  = $validated['exec_director_name'] ?? null;

        // signature (data URL -> PNG file)
        if (!empty($validated['exec_director_signature_data'])) {
            $dataUrl = $validated['exec_director_signature_data']; // "data:image/png;base64,...."
            if (str_starts_with($dataUrl, 'data:image/png;base64,')) {
                $png = base64_decode(substr($dataUrl, strpos($dataUrl, ',') + 1));

                // delete prior file if exists
                if (!empty($app->exec_director_signature_path)) {
                    Storage::disk('public')->delete($app->exec_director_signature_path);
                }

                $path = 'app-images/exec_signature_' . Str::random(8) . '.png';
                Storage::disk('public')->put($path, $png);
                $app->exec_director_signature_path = $path;
            }
        }

        // analytics
        $app->ga_measurement_id   = $validated['ga_measurement_id'] ?? null;
        $app->gtm_container_id    = $validated['gtm_container_id'] ?? null;
        $scripts = array_values(array_filter($validated['analytics_scripts'] ?? [], fn ($s) => trim((string)$s) !== ''));
        $app->analytics_scripts   = $scripts ?: null;

        $app->accreditation_image_alt = $validated['accreditation_image_alt'] ?? null;

        $app->save();

        AppSetting::refreshCache();


        return back()->with('success', 'App settings updated.');
    }



}
