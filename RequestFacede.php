<?php

namespace Tayron;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Classe que trata e gerencia informações de requisições
 *
 * @author Tayron Miranda <dev@tayron.com.br>
 */
final class RequestFacede implements RequestInterface
{
    /**
     * Objeto responsavel por gerenciar dados de requisição
     * @var Request
     */
    private $request;
    
    /**
     * RequestFacede::__construct
     */
    public function __construct() 
    {
        $this->request = new Request();
    }
    
    /**
     * RequestFacede::redirect
     *
     * @see RequestInterface::redirect($location)
     * @return void
     */
    final public function redirect($location) 
    {
        $redirect = new RedirectResponse($location);
        $redirect->send();
    }

    /**
     * RequestFacede::getUri
     *
     * @see RequestInterface::getUri()
     * @return array Lista de parametros da requisições uri
     */
    final public function getUri() 
    {
        return $this->request->getUri();
    }

    /**
     * RequestFacede::getPostParameter
     *
     * @see RequestInterface::getPostParameter($key)
     * @return mixed Valor aramazenado via POST
     */
    final public function getPostParameter($key = null) 
    {
        return $this->request->get($key);
    }

    /**
     * RequestFacede::getGetParameter
     *
     * @see RequestInterface::getGetParameter($key)
     * @return mixed Valor aramazenado via GET
     */
    final public function getGetParameter($key = null) 
    {
        $this->request->query->get($key);        
    }
    
    /**
     * RequestFacede::getPutParameter
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
     * RequestFacede::url
     *
     * @see RequestInterface::url($location)
     * @return string Retorna url completa com o link informado
     */
    final public function url($location = null) 
    {
        return $this->getUri() . $location;
    }

    /**
     * RequestFacede::isPost
     *
     * @see RequestInterface::isPost()
     * @return boolean Retorna true caso a requisição seja feita via POST
     */
    final public function isPost() 
    {
        return $this->request->isMethod(Request::METHOD_POST);
    }

    /**
     * RequestFacede::isGet
     *
     * @see RequestInterface::isGet()
     * @return boolean Retorna true caso a requisição seja feita via GET
     */
    final public function isGet() 
    {
        return $this->request->isMethod(Request::METHOD_GET);
    }
    
    /**
     * RequestFacede::isDelete
     *
     * @see RequestInterface::isDelete()
     * @return boolean Retorna true caso a requisição seja feita via DELETE
     */
    final public function isDelete() 
    {
        return $this->request->isMethod(Request::METHOD_DELETE);
    }    
    
    /**
     * RequestFacede::isPut
     *
     * @see RequestInterface::isPut()
     * @return boolean Retorna true caso a requisição seja feita via PUT
     */
    final public function isPut() 
    {
        return $this->request->isMethod(Request::METHOD_PUT);        
    }
}