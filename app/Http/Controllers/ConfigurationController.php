<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        $emails = Email::all();
        $configurations = Configuration::whereIn('key', ['report_time', 'opening_time', 'closing_time'])->get();

        return view('admin.configurations.index', compact('emails', 'configurations'));
    }

    public function storeEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:emails,email']);

        Email::create($request->only('email'));

        return redirect()->back()->with('success', 'Email añadido exitosamente.');
    }

    public function deleteEmail($id)
    {
        Email::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Email eliminado exitosamente.');
    }

    public function store(Request $request)
    {

        $request->validate([
            'key' => 'required|string|unique:configurations,key',
            'value' => 'required|string',
        ]);

        Configuration::create($request->only('key', 'value'));

        return redirect()->back()->with('success', 'Configuración añadida exitosamente.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|string',
        ]);

        $configuration = Configuration::findOrFail($id);
        $configuration->update(['value' => $request->input('value')]);

        return redirect()->back()->with('success', 'Configuración actualizada exitosamente.');
    }

}