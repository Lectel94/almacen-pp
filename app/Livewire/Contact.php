<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Contact extends Component
{

    // Propiedades del formulario
    public $name = '';
    public $email = '';
    public $message = '';

    // Validaciones
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:2000',
    ];

    // Enviar formulario
    public function send()
    {
        // Validar datos
        $this->validate();

        // Aquí puedes enviar email, guardar en base, etc.
        // Ejemplo enviando email
        Mail::send('store.emails.contact', [
            'name' => $this->name,
            'email' => $this->email,
            'messageContent' => $this->message,
        ], function ($mail) {
            $mail->to('info@example.com') // cambiar por tu dirección
                ->subject('Nuevo mensaje de contacto');
        });

        // Limpiar formulario después del envío
        $this->reset(['name', 'email', 'message']);

        // Mostrar mensaje de éxito
        session()->flash('success', 'Tu mensaje ha sido enviado con éxito.');
    }


    public function render()
    {
        return view('livewire.contact');
    }
}
