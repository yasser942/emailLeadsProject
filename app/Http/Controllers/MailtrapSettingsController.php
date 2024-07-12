<?php

// app/Http/Controllers/MailtrapSettingsController.php

namespace App\Http\Controllers;

use App\Models\MailtrapSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MailtrapSettingsController extends Controller
{
    public function index()
    {
        $settings = MailtrapSettings::first();
        if (!$settings) {
            $settings = MailtrapSettings::create([
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'mailer' => env('MAIL_MAILER', 'smtp'),
                'host' => env('MAIL_HOST', 'mailpit'),
                'port' => env('MAIL_PORT', 1025),
                'encryption' => env('MAIL_ENCRYPTION'),
                'from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'from_name' => env('MAIL_FROM_NAME', env('APP_NAME')),
            ]);
        }

        return view('mailtrap.index', compact('settings'));
    }

    public function edit()
    {
        $settings = MailtrapSettings::first();
        return view('mailtrap.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'encryption' => 'nullable|string',
            'from_address' => 'required|string|email',
            'from_name' => 'required|string',
        ]);

        $settings = MailtrapSettings::first();
        if ($settings) {
            $settings->update($request->all());

            // Update .env file with new credentials
            $this->updateEnv('MAIL_USERNAME', $request->username);
            $this->updateEnv('MAIL_PASSWORD', $request->password);
            $this->updateEnv('MAIL_MAILER', $request->mailer);
            $this->updateEnv('MAIL_HOST', $request->host);
            $this->updateEnv('MAIL_PORT', $request->port);
            $this->updateEnv('MAIL_ENCRYPTION', $request->encryption);
            $this->updateEnv('MAIL_FROM_ADDRESS', $request->from_address);
            $this->updateEnv('MAIL_FROM_NAME', $request->from_name);

            return redirect()->route('mailtrap.index')->with('success', 'Mailtrap settings updated successfully.');
        }

        return redirect()->route('mailtrap.index')->with('error', 'Mailtrap settings not found.');
    }

    private function updateEnv($key, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $env = file($path);
            foreach ($env as &$line) {
                if (strpos($line, $key) === 0) {
                    $line = "{$key}={$value}\n";
                    break;
                }
            }
            file_put_contents($path, implode('', $env));
            Artisan::call('config:cache'); // Clear and cache the config
        }
    }
}