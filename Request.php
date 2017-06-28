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
        self::$instance->urlPath = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) 
            ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'];

        // Armazena a url base do camiho onde o sistema está, exemplo: / ou /diretorio/matriz/
        self::$instance->urlBase = str_replace('index.php', null, $_SERVER['SCRIPT_NAME']);

        // Armazena a url corrente, exemplo http://diretorio/onframework/clients/gerar-relatorio
        self::$instance->urlCurrent = URL_PATH . $_SERVER['REQUEST_URI'];

        return self::$instance;
    }

    /**
     * Request::redirect
     *
     * @see RequestInterface::redirect($controllerAction)
     * @return void
     */
    final public function redirect(array $controllerAction) 
    {
        $controller = (isset($controllerAction['controller']) && $controllerAction['controller'] !== null) 
            ? $controllerAction['controller'] : null;

        $action = (isset($controllerAction['action']) && $controllerAction['action'] !== null) 
            ? $controllerAction['action'] : null;

        if ($controller == null && $action == null) {
            throw new InvalidArgumentException('redirect', 
                'Deve-se informar um controller e/ou uma action para se efetuar o redirect');
        }

        if ($controller != null && $action != null) {
            $this->redirectTo("/$controller/$action");
        }

        if ($controller != null && $action == null) {
            $this->redirectTo("/$controller");
        }

        if ($controller == null && $action != null) {
            $this->redirectTo("/$action");
        }
    }
    
    /**
     * Request::redirectTo
     *
     * Método que retorna para uma página qualquer
     *
     * @param string $location Local para onde será redirecionado
     * 
     * @return void
     */    
    private function redirectTo($location)
    {        
        header("Location $location");
    }

    /**
     * Request::getUri
     *
     * @see RequestInterface::getUri()
     * @return array Lista de parametros da requisições uri
     */
    final public function getUri() 
    {
        $itenListUri = explode('/', str_replace($this->urlPath . $this->urlBase, 
            null, $this->urlCurrent));

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
     * @see RequestInterface::getPostParameter($key)
     * @return mixed Valor aramazenado via POST
     */
    final public function getPostParameter($key = null) 
    {
        return $this->getParameters(INPUT_POST, $key);
    }

    /**
     * Request::getGetParameter
     *
     * @see RequestInterface::getGetParameter($key)
     * @return mixed Valor aramazenado via GET
     */
    final public function getGetParameter($key = null) 
    {
        return $this->getParameters(INPUT_GET, $key);
    }
    
    /**
     * Request::getPutParameter
     *
     * @see RequestInterface::getPutParameter($key)
     * @return mixed Valor aramazenado via PUT
     */    
    final public function getPutParameter($key = null)
    {
        $parameters = null;
        parse_str(file_get_contents("php://input"), $parameters);

        if(is_null($key)){
            return $parameters;
        }
        
        if(isset($parameters[$key])){
            return $parameters[$key];
        }
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
        
        if(!$parameters){
            return null;
        }

        if ($key && isset($parameters[$key])) {
            return $parameters[$key];
        }

        if ($key == null) {
            return array_map(function(&$value) {
                return $value;
            }, $parameters);
        }
    }

    /**
     * Request::url
     *
     * @see RequestInterface::url($controllerAction)
     * @return string Retorna url completa com o link informado
     */
    final public function url($controllerAction = null) 
    {
        return $this->urlPath . str_replace('//', '/', $this->urlBase . $controllerAction);
    }

    /**
     * Request::isPost
     *
     * @see RequestInterface::isPost()
     * @return boolean Retorna true caso a requisição seja feita via POST
     */
    final public function isPost() 
    {
        return $this->is('POST');
    }

    /**
     * Request::isGet
     *
     * @see RequestInterface::isGet()
     * @return boolean Retorna true caso a requisição seja feita via GET
     */
    final public function isGet() 
    {
        return $this->is('GET');
    }
    
    /**
     * Request::isDelete
     *
     * @see RequestInterface::isDelete()
     * @return boolean Retorna true caso a requisição seja feita via DELETE
     */
    final public function isDelete() 
    {
        return $this->is('DELETE');
    }    
    
    /**
     * Request::isPut
     *
     * @see RequestInterface::isPut()
     * @return boolean Retorna true caso a requisição seja feita via PUT
     */
    final public function isPut() 
    {
        return $this->is('PUT');
    }
    
    /**
     * Request::is
     *
     * Método que informa se a requisição feita foi via GET OU POST
     *
     * @return boolean Retorna true caso a requisição seja feita via GET ou POST
     */    
    private function is($method)
    {
        return $_SERVER['REQUEST_METHOD'] === $method;
    }
}
