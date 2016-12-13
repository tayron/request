<?php

namespace Tayron;

use \InvalidArgumentException;
use \Exception;

/**
 * Classe que trata e gerencia informações de requisições
 *
 * @author Tayron Miranda <dev@tayron.com.br>
 */
final class Request 
{
    /**
     * Armazena a url base do servidor, exemplo: http://localhost ou http://localhost:81
     * @var string
     */
    private $urlPath = null;

    /**
     * Armazena a url base do camiho onde o sistema está, exemplo: / ou /diretorio/matriz/
     * @var string
     */
    private $urlBase = null;

    /**
     * Armazena a url corrente, exemplo http://diretorio/onframework/clients/gerar-relatorio
     * @var string
     */
    private $urlCurrent = null;

    /**
     * @var Singleton reference to singleton instance
     */
    private static $instance;

    /**
     * Request::__construct
     *
     * Impede com que o objeto seja instanciado
     */
    final private function __construct() 
    {
        
    }

    /**
     * Request::__clone
     *
     * Impede que a classe Requisição seja clonada
     *
     * @throws Execao Lança execção caso o usuário tente clonar este classe
     *
     * @return void
     */
    final public function __clone() 
    {
        throw new Exception('A classe Request não pode ser clonada.');
    }

    /**
     * Request::__wakeup
     *
     * Impede que a classe Requisição execute __wakeup
     *
     * @throws Execao Lança execção caso o usuário tente executar este método
     *
     * @return void
     */
    final public function __wakeup() 
    {
        throw new Exception('A classe Request não pode executar __wakeup.');
    }

    /**
     * Request::getInstance
     *
     * Retorna uma instância única de uma classe.
     *
     * @staticvar Singleton $instancia A instância única dessa classe.
     *
     * @return Singleton A Instância única.
     */
    public static function getInstance() 
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        // Armazena a url base do servidor, exemplo: http://localhost ou http://localhost:81
        self::$instance->urlPath = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'];

        // Armazena a url base do camiho onde o sistema está, exemplo: / ou /diretorio/matriz/
        self::$instance->urlBase = str_replace('index.php', null, $_SERVER['SCRIPT_NAME']);

        // Armazena a url corrente, exemplo http://diretorio/onframework/clients/gerar-relatorio
        self::$instance->urlCurrent = URL_PATH . $_SERVER['REQUEST_URI'];

        return self::$instance;
    }

    /**
     * Request::redirect
     *
     * Método que redireciona para uma outra tela
     *
     * @param array $controllerAction Lista com nome do controller e action
     * @return void
     */
    public function redirect(array $controllerAction) 
    {
        $controller = (isset($controllerAction['controller']) && $controllerAction['controller'] !== null) ? $controllerAction['controller'] : null;

        $action = (isset($controllerAction['action']) && $controllerAction['action'] !== null) ? $controllerAction['action'] : null;

        if ($controller == null && $action == null) {
            throw new InvalidArgumentException('redirect', 'Deve-se informar um controller e/ou uma action para se efetuar o redirect');
        }

        if ($controller != null && $action != null) {
            header("Location: /$controller/$action");
        }

        if ($controller != null && $action == null) {
            header("Location: /$controller");
        }

        if ($controller == null && $action != null) {
            header("Location: /$action");
        }
    }

    /**
     * Request::getUri
     *
     * Método que retorna uma lista de parametros informados na URI
     *
     * @return array Lista de parametros da requisições uri
     */
    public function getUri() 
    {
        $itenListUri = explode('/', str_replace($this->urlPath . $this->urlBase, null, $this->urlCurrent));

        $itensList = array();
        foreach ($itenListUri as $item) {
            $parameters = explode('?', $item);
            array_push($itensList, current($parameters));
        }

        return array_filter($itensList);
    }

    /**
     * Request::getPostParameter
     *
     * Metodo que retorna os parametros enviados via POST
     *
     * @param string $key Nome do parametro a ser recuperado
     * @return mixed Valor aramazenado via POST
     */
    public function getPostParameter($key = null) 
    {
        return $this->getParameters(INPUT_POST, $key);
    }

    /**
     * Request::getGetParameter
     *
     * Metodo que retorna os parametros enviados via GET
     *
     * @param string $key Nome do parametro a ser recuperado
     * @return mixed Valor aramazenado via GET
     */
    public function getGetParameter($key = null) 
    {
        return $this->getParameters(INPUT_GET, $key);
    }

    /**
     * Request::getParametros
     *
     * Método que retorna lista de parametros, seja via post ou via get
     *
     * @param string $type Tipo de parametro, sendo 1 para GET e 0 para POST
     * @param string $key Nome do parametro POST ou GET
     *
     * @return mixed Valor aramazenado via POST ou GET
     */
    private function getParameters($type, $key = null) 
    {
        $parameters = filter_input_array($type);

        if ($key && isset($parameters[$key])) {
            return htmlentities($parameters[$key]);
        }

        if ($key == null) {
            return array_map(function($key, &$value) {
                return htmlentities($value);
            }, $parameters);
        }
    }

    /**
     * Request::url
     *
     * Método que monta a retorna url para uma determinada pagina
     *
     * @param string $controllerAction Nome do controller + action podendo ter parametros via get
     * @return string Retorna url completa com o link informado
     */
    public function url($controllerAction = null) 
    {
        return $this->urlPath . str_replace('//', '/', $this->urlBase . $controllerAction);
    }

    /**
     * Request::isPost
     *
     * Método que informa se a requisição feita foi via POST
     *
     * @return boolean Retorna true caso a requisição seja feita via POST
     */
    public function isPost() 
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Request::isPost
     *
     * Método que informa se a requisição feita foi via GET
     *
     * @return boolean Retorna true caso a requisição seja feita via GET
     */
    public function isGet() 
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}