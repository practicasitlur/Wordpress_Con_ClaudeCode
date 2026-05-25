<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AOW_Odoo_Client {

    private $url;
    private $db;
    private $username;
    private $api_key;
    private $uid = null;

    public function __construct( $url, $db, $username, $api_key ) {
        $this->url      = rtrim( $url, '/' );
        $this->db       = $db;
        $this->username = $username;
        $this->api_key  = $api_key;
    }

    private function call( $service, $method, $args ) {
        $response = wp_remote_post(
            $this->url . '/jsonrpc',
            [
                'timeout'     => 30,
                'headers'     => [ 'Content-Type' => 'application/json' ],
                'body'        => wp_json_encode( [
                    'jsonrpc' => '2.0',
                    'method'  => 'call',
                    'id'      => 1,
                    'params'  => [
                        'service' => $service,
                        'method'  => $method,
                        'args'    => $args,
                    ],
                ] ),
            ]
        );

        if ( is_wp_error( $response ) ) {
            throw new Exception( 'Error de conexión con Odoo: ' . $response->get_error_message() );
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( isset( $body['error'] ) ) {
            throw new Exception( 'Error Odoo: ' . $body['error']['data']['message'] );
        }

        return $body['result'];
    }

    public function authenticate() {
        $this->uid = $this->call( 'common', 'authenticate', [
            $this->db,
            $this->username,
            $this->api_key,
            [],
        ] );

        if ( ! $this->uid ) {
            throw new Exception( 'Autenticación fallida. Revisa las credenciales de Odoo.' );
        }

        return $this->uid;
    }

    public function get_last_order_date( $partner_id ) {
        if ( ! $this->uid ) {
            $this->authenticate();
        }

        $pedidos = $this->call( 'object', 'execute_kw', [
            $this->db,
            $this->uid,
            $this->api_key,
            'sale.order',
            'search_read',
            [ [
                [ 'partner_id', '=', (int) $partner_id ],
                [ 'state', 'in', [ 'sale', 'done' ] ],
            ] ],
            [
                'fields' => [ 'date_order' ],
                'limit'  => 1,
                'order'  => 'date_order desc',
            ],
        ] );

        return ! empty( $pedidos ) ? $pedidos[0]['date_order'] : null;
    }
}
