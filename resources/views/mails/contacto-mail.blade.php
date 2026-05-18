@component('mail::message')
# Nuevo Mensaje de Contacto

¡Hola!

Has recibido un nuevo mensaje desde el formulario de contacto de Badia Padel Tour.

**Nombre:** {{ $nombre }}
**Email:** {{ $email }}

**Mensaje:**

{{ $mensaje }}

---

Este es un mensaje automático. Por favor, responde al email del remitente.

Saludos,  
El equipo de Badia Padel Tour
@endcomponent
