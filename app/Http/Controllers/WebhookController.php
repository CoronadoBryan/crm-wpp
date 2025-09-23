<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConversacionController;

class WebhookController extends Controller
{
    protected $clienteController;
    protected $conversacionController;

    public function __construct(
        ClienteController $clienteController,
        ConversacionController $conversacionController
    ) {
        $this->clienteController = $clienteController;
        $this->conversacionController = $conversacionController;
    }

    public function verify(Request $request)
    {
        $verifyToken = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');
        
        Log::info('Webhook verify - Token: ' . $verifyToken);
        
        if ($verifyToken === env('WHATSAPP_VERIFY_TOKEN')) {
            Log::info('✅ Webhook verificado correctamente');
            return response($challenge);
        }
        
        Log::error('❌ Token inválido');
        return response('Token inválido', 403);
    }

    public function receive(Request $request)
    {
        $data = $request->all();
        Log::info('📨 Webhook recibido');
        Log::info('🔍 Payload: ' . json_encode($data));
        
        if (isset($data['entry'])) {
            foreach ($data['entry'] as $entry) {
                if (isset($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        if ($change['field'] === 'messages') {
                            $this->procesarMensajesEntrantes($change['value']);
                        } elseif ($change['field'] === 'message_status') {
                            $this->procesarEstadoMensajesSalientes($change['value']);
                        } else {
                            Log::info("🔍 Campo no procesado: " . $change['field']);
                        }
                    }
                }
            }
        }
        return response('OK', 200);
    }

    // Solo gestiona el flujo, delega la lógica a los controladores
    private function procesarMensajesEntrantes($data)
    {
        Log::info('📥 Procesando mensajes ENTRANTES');
        if (isset($data['messages'])) {
            foreach ($data['messages'] as $mensaje) {
                $this->gestionarMensajeEntrante($mensaje, $data);
            }
        }
    }

    private function gestionarMensajeEntrante($mensaje, $data)
    {
        $telefono = $mensaje['from'];
        $contenido = $mensaje['text']['body'] ?? 'Multimedia';
        $alias = $this->extraerAlias($data, $telefono);

        Log::info("📞 Teléfono entrante: " . $telefono);
        if ($alias) {
            Log::info("👤 Alias: " . $alias);
        }

        // Toda la lógica de cliente en ClienteController
        $resultado = $this->clienteController->buscarOCrearCliente($telefono, $alias);

        if ($resultado) {
            $cliente = $resultado['cliente'];
            $esNuevo = $resultado['es_nuevo'];

            // Toda la lógica de conversación en ConversacionController
            $this->conversacionController->gestionarConversacion($cliente, $esNuevo);

            $nombre_mostrar = $alias ? "({$alias})" : '';
            $accion = $esNuevo ? 'creado' : 'encontrado';
            Log::info("📱 Mensaje ENTRANTE de {$telefono} {$nombre_mostrar} ({$accion}): {$contenido}");
        }
    }

    private function procesarEstadoMensajesSalientes($data)
    {
        Log::info('📤 Procesando estados SALIENTES');
        if (isset($data['statuses'])) {
            foreach ($data['statuses'] as $status) {
                $mensaje_id = $status['id'];
                $telefono = $status['recipient_id'];
                $estado = $status['status'];
                Log::info("📤 Estado: {$mensaje_id} -> {$telefono} ({$estado})");
                // Si necesitas actualizar el estado de mensajes, delega a un controlador de mensajes aquí
            }
        }
    }

    private function extraerAlias($data, $telefono)
    {
        if (isset($data['contacts'])) {
            foreach ($data['contacts'] as $contact) {
                if ($contact['wa_id'] === $telefono) {
                    return $contact['profile']['name'] ?? null;
                }
            }
        }
        return null;
    }
}
