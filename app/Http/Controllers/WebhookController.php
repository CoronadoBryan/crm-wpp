<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Verificar el webhook (GET request)
     */
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

    /**
     * Recibir mensajes (POST request)
     */
    public function receive(Request $request)
    {
        $data = $request->all();
        
        Log::info('📨 Webhook recibido');
        Log::info('🔍 Payload original:', $data);
        
        // Procesar mensajes
        if (isset($data['entry'])) {
            foreach ($data['entry'] as $entry) {
                if (isset($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        if ($change['field'] === 'messages') {
                            $this->procesarMensaje($change['value']);
                        }
                    }
                }
            }
        }
        
        return response('OK', 200);
    }

    /**
     * Procesar mensaje recibido
     */
    private function procesarMensaje($data)
    {
        if (isset($data['messages'])) {
            foreach ($data['messages'] as $mensaje) {
                $telefono = $mensaje['from'];
                $contenido = $mensaje['text']['body'] ?? 'Multimedia';
                
                // Extraer alias del perfil si existe
                $alias = null;
                if (isset($data['contacts'])) {
                    foreach ($data['contacts'] as $contact) {
                        if ($contact['wa_id'] === $telefono) {
                            $alias = $contact['profile']['name'] ?? null;
                            break;
                        }
                    }
                }
                
                Log::info("📞 Teléfono recibido: " . $telefono);
                if ($alias) {
                    Log::info("👤 Alias encontrado: " . $alias);
                }
                
                try {
                    // Crear o buscar cliente - solo guardar alias si existe
                    $cliente = Clientes::firstOrCreate(
                        ['telefono' => $telefono],
                        ['alias' => $alias]
                    );
                    
                    $accion = $cliente->wasRecentlyCreated ? 'creado' : 'encontrado';
                    $nombreMostrar = $alias ? "({$alias})" : '';
                    Log::info("📱 Mensaje de {$telefono} {$nombreMostrar} ({$accion}): {$contenido}");
                    
                } catch (\Exception $e) {
                    Log::error("❌ Error procesando mensaje de {$telefono}: " . $e->getMessage());
                }
            }
        }
    }
}
