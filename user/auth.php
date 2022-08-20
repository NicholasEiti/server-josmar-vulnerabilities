<?php
/**
 * Criar Usuário
 */

require_once "../library/library.php";

// require_once __ROOT__ . "/library/sessionHandler.class.php";

// new JosmarSessionHandler;

session_start();

API::send_success('key_created', ['id' => session_id()]);
